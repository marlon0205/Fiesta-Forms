<?php

namespace App\View\Components\Survey;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Explore extends Component {
    public $surveys;
    public $categories;
    public $search;
    public $selectedCategory;


    public function __construct(Request $request) {
        /**
         * the logic behind the explore page is the following:
         * We load the tab and check for filters set (example: ?search=Book&category=IT)
         * When filters are set then we directly apply them into the query
         *
         * We also need to load the categories for the dropdown
         */

        $this->search = $request->input('search');
        $this->selectedCategory = $request->input('category');


        /**
         * loading categories for the dropdown
         *
         * Difference between pluck and all
         *
         * pluck returns a lightweight Collection of raw Strings
         * all returns a Collection of the Models where only name is returned
         *
         * pluck:
         * ["IT", "Food", "Sports"]
         *
         * all:
         * [{"name": "IT"}, {"name": "Food"}, {"name": "Sports"}]
         */
        $services = Service_Categories::pluck('name');
        $products = Product_Categories::pluck('name');
        // merging both arrays into one
        $this->categories = $services->merge($products)->sort()->values();

        $this->surveys = Survey::select('id', 'title', 'description', 'category')
            ->where('title', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->where('serviceCategory', $this->selectedCategory)
            ->orWhere('productCategory', $this->selectedCategory);

    }

    public function render() {
        // TODO: Link correct blade file from resources/views/components
        return view('components.survey.explore');
    }
}
