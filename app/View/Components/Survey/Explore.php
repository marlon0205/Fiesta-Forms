<?php

namespace App\View\Components\Survey;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Explore extends Component
{
    public $surveys;
    public $productCategories;
    public $serviceCategories;
    public $search;
    public $selectedProductCategory;
    public $selectedServiceCategory;
    public $selectedStatus;

    public function __construct(Request $request)
    {
        $this->search = $request->input('search');
        $this->selectedProductCategory = $request->input('product_category');
        $this->selectedServiceCategory = $request->input('service_category');
        $this->selectedStatus = $request->input('status');

        $this->productCategories = Product_Categories::all();
        $this->serviceCategories = Service_Categories::all();

        $query = Survey::with(['productCategory', 'serviceCategory'])->withCount('votes');

        // Apply Search Filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });

            // Order by relevance if searching
            $query->orderByRaw("CASE WHEN title LIKE ? THEN 1 ELSE 2 END", ['%' . $this->search . '%']);
        } else {
            // Default ordering
            $query->latest();
        }

        // Apply Category Filters
        if ($this->selectedProductCategory) {
            $query->whereHas('productCategory', function ($q) {
                $q->where('name', $this->selectedProductCategory);
            });
        }

        if ($this->selectedServiceCategory) {
            $query->whereHas('serviceCategory', function ($q) {
                $q->where('name', $this->selectedServiceCategory);
            });
        }

        // Apply Status Filter
        if ($this->selectedStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->selectedStatus === 'inactive') {
            $query->where('is_active', false);
        }

        // Execute query and map results for the view
        $this->surveys = $query->get()->map(function ($survey) {
            return [
                'survey_id' => $survey->survey_id,
                'title' => $survey->title,
                'description' => $survey->description,
                // Ensure is_active is mapped to 'active' string if true, as expected by the view
                'is_active' => $survey->is_active ? 'active' : 'expired',
                'status' => $survey->is_active ? 'Active' : 'Expired',
                // Determine category: either product or service
                'category' => $survey->productCategory->name ?? $survey->serviceCategory->name ?? 'General',
                'submissions' => $survey->votes_count,
            ];
        });
    }

    public function render()
    {
        return view('components.survey.explore');
    }
}
