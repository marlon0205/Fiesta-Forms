<?php

namespace App\View\Components\Survey;

use App\Models\Service_Categories;
use App\Models\Product_Categories;
use Illuminate\View\Component;
use Illuminate\View\View;

class CreateForm extends Component {

    /**
     * $categories is available in the blade file.
     */
    public $productCategories;
    public $serviceCategories;

    public function __construct() {
        $this->serviceCategories = Service_Categories::pluck('name');
        $this->productCategories  = Product_Categories::pluck('name');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View {
        /**
         * currently uses an example blade file, waiting for merge branch #15
         */
        return view('components.survey.create-form');
    }
}
