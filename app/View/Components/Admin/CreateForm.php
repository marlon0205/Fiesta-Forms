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
    public $categories;

    public function __construct() {
        // TODO: what was the difference between product and service category? Can we list them in the same dropdown? If yes, that's the way.
        $services = Service_Categories::pluck('name');
        $products = Product_Categories::pluck('name');

        $this->categories = $services->merge($products);
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
