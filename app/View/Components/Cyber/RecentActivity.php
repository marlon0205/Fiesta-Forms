<?php

namespace App\View\Components\Cyber;

use App\Models\Survey;
use Illuminate\View\Component;

class RecentActivity extends Component
{
    public $recentSurveys;

    public function __construct()
    {
        $this->recentSurveys = Survey::with(['serviceCategory', 'productCategory'])
            ->withCount('votes')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($survey) => [
                'id' => $survey->survey_id,
                'title' => $survey->title,
                'description' => $survey->description,
                'category' => $survey->serviceCategory->name ?? $survey->productCategory->name ?? 'General',
                'status' => $survey->is_active ? 'active' : 'expired',
                'submissions' => $survey->votes_count,
            ]);
    }

    public function render()
    {
        return view('components.cyber.recent-activity');
    }
}
