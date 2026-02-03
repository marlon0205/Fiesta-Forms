<?php

namespace App\View\Components\Dashboard;

use App\Models\Survey;
use App\Models\Votes;
use Illuminate\View\Component;

class RecentActivity extends Component {

    public $surveys;

    public function __construct() {
        $this->surveys = Survey::latest()
            ->take(5)->get();
    }

    public function render() {
        // TODO: Link correct blade file from resources/views/components
        return view('components.dashboard.recent-activity');
    }
}
