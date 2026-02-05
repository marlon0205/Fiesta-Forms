<?php

namespace App\View\Components\Dashboard;

use App\Models\Survey;
use Illuminate\View\Component;

class RecentActivity extends Component {
    public $recentSurveys;

    public function __construct() {
        $this->recentSurveys = Survey::latest()
            ->take(3)
            ->get()
            ->map(fn($survey) => [
                'id' => $survey->survey_id,
                'title' => $survey->title,
                'description' => $survey->description,
                'category' => $survey->serviceCategory->name ?? $survey->productCategory->name ?? 'General',
                'status' => $survey->is_active ? 'active' : 'expired',
                'submissions' => $survey->votes()->count()
            ]);
    }

    public function render() {
        return view('components.dashboard.recent-activity');
    }
}