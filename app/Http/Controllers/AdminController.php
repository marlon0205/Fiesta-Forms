<?php

namespace App\Http\Controllers;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\Reward;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $activeTab = request('tab', 'forms');

        $sort = request('sort', 'created');
        $direction = request('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'title',
            'questions',
            'responses',
            'last_response',
            'status',
            'created',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created';
        }

        $surveysQuery = Survey::with(['user', 'serviceCategory', 'productCategory'])
            ->withCount(['votes', 'questions'])
            ->withMax('votes as last_response_at', 'created_at');

        switch ($sort) {
            case 'title':
                $surveysQuery->orderBy('title', $direction);
                break;
            case 'questions':
                $surveysQuery->orderBy('questions_count', $direction);
                break;
            case 'responses':
                $surveysQuery->orderBy('votes_count', $direction);
                break;
            case 'last_response':
                $surveysQuery->orderBy('last_response_at', $direction);
                break;
            case 'status':
                $surveysQuery->orderBy('is_active', $direction);
                break;
            case 'created':
            default:
                $surveysQuery->orderBy('created_at', $direction);
                break;
        }

        $surveys = $surveysQuery
            ->orderBy('survey_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $userSort = request('user_sort', 'created');
        $userDirection = request('user_direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $allowedUserSorts = [
            'name',
            'email',
            'role',
            'votes',
            'last_activity',
            'created',
        ];

        if (! in_array($userSort, $allowedUserSorts, true)) {
            $userSort = 'created';
        }

        $lastActivityQuery = DB::table('sessions')
            ->select('user_id', DB::raw('MAX(last_activity) as last_activity_at'))
            ->whereNotNull('user_id')
            ->groupBy('user_id');

        $usersQuery = User::query()
            ->select('users.*', 'user_last_activity.last_activity_at')
            ->with('roles')
            ->withCount('votes')
            ->leftJoinSub($lastActivityQuery, 'user_last_activity', function ($join) {
                $join->on('users.user_id', '=', 'user_last_activity.user_id');
            });

        switch ($userSort) {
            case 'name':
                $usersQuery->orderBy('name', $userDirection);
                break;
            case 'email':
                $usersQuery->orderBy('email', $userDirection);
                break;
            case 'role':
                $usersQuery->orderBy(
                    DB::table('model_has_roles')
                        ->select('roles.name')
                        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->whereColumn('model_has_roles.model_id', 'users.user_id')
                        ->where('model_has_roles.model_type', User::class)
                        ->limit(1),
                    $userDirection
                );
                break;
            case 'votes':
                $usersQuery->orderBy('votes_count', $userDirection);
                break;
            case 'last_activity':
                $usersQuery->orderBy('user_last_activity.last_activity_at', $userDirection);
                break;
            case 'created':
            default:
                $usersQuery->orderBy('created_at', $userDirection);
                break;
        }

        $users = $usersQuery
            ->orderBy('users.user_id', 'desc')
            ->paginate(15, ['*'], 'users_page')
            ->withQueryString();

        $rewards = Reward::query()
            ->orderBy('points_required')
            ->paginate(15, ['*'], 'rewards_page')
            ->withQueryString();

        return view('cyber.admin', compact('surveys', 'users', 'rewards'));
    }

    public function storeReward(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'points_required' => ['required', 'integer', 'min:1', Rule::unique('rewards', 'points_required')],
        ]);

        Reward::create($validated);

        return redirect()
            ->route('dashboard.admin', ['tab' => 'rewards'])
            ->with('success', 'Reward created successfully.');
    }

    public function updateReward(Request $request, Reward $reward): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'points_required' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('rewards', 'points_required')->ignore($reward->reward_id, 'reward_id'),
            ],
        ]);

        $reward->update($validated);

        return redirect()
            ->route('dashboard.admin', ['tab' => 'rewards'])
            ->with('success', 'Reward updated successfully.');
    }

    public function destroyReward(Reward $reward): RedirectResponse
    {
        $reward->delete();

        return redirect()
            ->route('dashboard.admin', ['tab' => 'rewards'])
            ->with('success', 'Reward deleted successfully.');
    }

    /**
     * Sync categories from external API or JSON.
     */
    public function syncCategories(Request $request): RedirectResponse
    {
        $request->validate([
            'api_url' => 'nullable|url',
            'api_token' => 'nullable|string',
            'json_data' => 'nullable|string',
        ]);

        $data = null;

        if ($request->api_url) {
            if (! $this->isSafeUrl($request->api_url)) {
                return back()->with('error', 'The provided URL is not allowed.');
            }

            try {
                $response = Http::withToken($request->api_token)
                    ->timeout(10)
                    ->get($request->api_url);

                if ($response->failed()) {
                    $body = $response->body();

                    // If it's HTML, strip tags first
                    if (str_contains($response->header('Content-Type', ''), 'html')) {
                        $body = strip_tags($body);
                    }

                    // Collapse all whitespace (newlines, tabs, multiple spaces) into a single space
                    $body = preg_replace('/\s+/', ' ', $body);
                    $body = trim(Str::limit($body, 150));

                    $errorMessage = $response->status() === 401 || $response->status() === 403
                        ? 'Authentication failed. Please check your token.'
                        : 'API Error (' . $response->status() . '): ' . $body;

                    return back()->with('error', $errorMessage);
                }

                $data = $response->json();
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Category sync API request failed: ' . $e->getMessage());
                return back()->with('error', 'Could not reach the API. Please check the URL and your connection.');
            }
        } elseif ($request->json_data) {
            $data = json_decode($request->json_data, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid JSON data provided.');
            }
        }

        if (! $data) {
            return back()->with('error', 'No data provided to sync.');
        }

        try {
            DB::transaction(function () use ($data) {
                if (isset($data['product_categories']) && is_array($data['product_categories'])) {
                    foreach ($data['product_categories'] as $cat) {
                        if (isset($cat['name'])) {
                            Product_Categories::firstOrCreate(['name' => $cat['name']]);
                        }
                    }
                }

                if (isset($data['service_categories']) && is_array($data['service_categories'])) {
                    foreach ($data['service_categories'] as $cat) {
                        if (isset($cat['name'])) {
                            Service_Categories::firstOrCreate(['name' => $cat['name']]);
                        }
                    }
                }
            });

            return redirect()->route('dashboard.admin', ['tab' => 'integrations'])
                ->with('success', 'Categories synced successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Category sync DB transaction failed: ' . $e->getMessage());
            return back()->with('error', 'Error syncing categories. Please try again.');
        }
    }

    private function isSafeUrl(string $url): bool
    {
        $parsed = parse_url($url);

        if (! isset($parsed['host'])) {
            return false;
        }

        $host = strtolower($parsed['host']);
        $blocked = ['localhost', '127.0.0.1', '0.0.0.0', '::1'];

        if (in_array($host, $blocked, true)) {
            return false;
        }

        $ip = gethostbyname($host);
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        return true;
    }
}

