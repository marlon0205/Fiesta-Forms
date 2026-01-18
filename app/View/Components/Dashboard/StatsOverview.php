<?php

namespace App\View\Components\Dashboard;

use App\Models\Survey;
use App\Models\Votes;
use Illuminate\View\Component;

class StatsOverview extends Component {
    public $totalForms;
    public $activePolls;
    public $totalResponses;
    public $impactScore;

    public function __construct() {
        $this->totalForms = Survey::count();
        $this->activePolls = Survey::where('is_active', true)->count();

        $this->totalResponses = Votes::count();
        $this->impactScore = $this->totalResponses / $this->totalForms;
    }

    public function render() {
        // TODO: Link correct blade file from resources/views/components
        return view('components.dashboard.stats-overview');
    }
}
