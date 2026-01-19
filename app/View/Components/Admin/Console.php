<?php

namespace App\View\Components\Admin;

use App\Models\Survey;
use Illuminate\View\Component;

class Console extends Component {
    public $surveys;

    public function __construct() {

        /**
         * TODO: is it good to have a Survey -> Question -> Votes relation?
         * What would be the advantage over Survey -> Votes?
         */

        $this->surveys = Survey::withCount('votes')
        ->latest()
            ->get();
    }

    public function render() {
        return view('components.admin.console');
    }
}
