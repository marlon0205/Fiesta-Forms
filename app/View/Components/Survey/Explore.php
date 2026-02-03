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
    public $selectedProductCategory;
    public $selectedServiceCategory;


    public function __construct(Request $request) {
        /**
         * the logic behind the explore page is the following:
         * We load the tab and check for filters set (example: ?search=Book&category=IT)
         * When filters are set then we directly apply them into the query
         *
         * We also need to load the categories for the dropdown
         */

        $this->search = $request->input('search');
        $this->selectedProductCategory = $request->input('product_category');
        $this->selectedServiceCategory = $request->input('service_category');

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

        // validate that the selected product and service category is a valid category
        if ($services->contains($this->selectedServiceCategory) or $products->contains($this->selectedProductCategory)){
            $this->surveys = Survey::select('id', 'title', 'description', 'category')
                ->whereAny(['title', 'description'], 'like', '%' . $this->search . '%')
                ->whereAny(['productCategory'], $this->selectedProductCategory)
                ->whereAny(['serviceCategory'], $this->selectedServiceCategory)
                // apparently this puts the searches with the title on top, thanks gemini
                ->orderByRaw("CASE WHEN title LIKE ? THEN 1 ELSE 2 END", ['%' . $this->search . '%'])
                ->get();
        }

    }

    public function render() {
        // TODO: Link correct blade file from resources/views/components
        return view('components.survey.explore');
    }
}
