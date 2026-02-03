<?php

namespace App\View\Components\Admin;

use App\Models\Survey;
use Illuminate\View\Component;

class Console extends Component {
    public $surveys;

    public function __construct() {
        $this->surveys = Survey::withCount('votes')
        ->latest()
            ->get();
    }

    public function render() {
        return view('components.admin.console');
    }
}
