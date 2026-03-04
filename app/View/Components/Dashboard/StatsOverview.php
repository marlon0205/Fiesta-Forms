<?php

namespace App\View\Components\Dashboard;

use App\Models\Survey;
use App\Models\User;
use App\Models\Votes;
use Illuminate\View\Component;

class StatsOverview extends Component
{
    public $totalForms;
    public $activePolls;
    public $totalResponses;
    public $totalUsers;

    public function __construct()
    {
        $this->totalForms = Survey::count();
        $this->activePolls = Survey::where('is_active', true)->count();

        $this->totalResponses = Votes::count();
        $this->totalUsers = User::count();
    }

    public function render()
    {
        return view('components.dashboard.stats-overview');
    }
}
