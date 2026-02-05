<?php

namespace App\View\Components\Dashboard;

use App\Models\Votes;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class TopUsers extends Component {

    public $votes;

    public function __construct() {
        $this->votes = Votes::select('user_id')
            ->selectRaw('count(*) as total_votes')
            ->groupBy('user_id')
            ->orderByDesc('total_votes')
            ->take(5)
            ->with('user')
            ->get()
            ->map(fn($vote) => [
                'name' => $vote->user->name ?? 'Anonymous Voter',
                'votes' => $vote->total_votes
            ]);
    }

    public function render() {
        return view('components.cyber.top-voters', [
            'voters' => $this->votes
        ]);
    }
}
