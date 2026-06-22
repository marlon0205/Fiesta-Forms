<?php

namespace App\View\Components\Cyber;

use App\Models\Votes;
use Illuminate\View\Component;

class TopUsers extends Component
{
    public $votes;

    public function __construct()
    {
        $this->votes = Votes::select('users.name')
            ->selectRaw('COUNT(votes.vote_id) as total_votes')
            ->join('users', 'votes.user_id', '=', 'users.user_id')
            ->groupBy('users.user_id', 'users.name')
            ->orderByDesc('total_votes')
            ->limit(5)
            ->get()
            ->map(fn($row) => [
                'name' => $row->name ?? 'Anonymous Voter',
                'votes' => $row->total_votes,
            ]);
    }

    public function render()
    {
        return view('components.cyber.top-voters', [
            'voters' => $this->votes,
        ]);
    }
}
