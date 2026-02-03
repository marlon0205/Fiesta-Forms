<?php

namespace App\View\Components\Dashboard;

use App\Models\Votes;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class TopUsers extends Component {

    public $votes;

    public function __construct() {

        // Either way should work fine, i want to test both when the blades are available
        $this->votes = DB::table('votes')
            ->select('user_id')
            ->selectRaw('count(*) as total_votes')
            ->groupBy('user_id')
            ->orderByDesc('total_votes')
            ->take(5)
            ->get();

        $this->votes = Votes::select('user_id')
            ->selectRaw('count(*) as total_votes')
            ->groupBy('user_id')
            ->orderByDesc('total_votes')
            ->take(5)
            ->with('user')
            ->get();
    }

    public function render() {
        // TODO: Link correct blade file from resources/views/components
        return view('components.dashboard.top-users');
    }
}
