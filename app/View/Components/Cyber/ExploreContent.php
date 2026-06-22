<?php

namespace App\View\Components\Cyber;

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class ExploreContent extends Component
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

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
            $query->orderByRaw("CASE WHEN title LIKE ? THEN 1 ELSE 2 END", ['%' . $this->search . '%']);
        } else {
            $query->latest();
        }

        if ($this->selectedProductCategory) {
            $query->whereHas('productCategory', fn($q) => $q->where('name', $this->selectedProductCategory));
        }

        if ($this->selectedServiceCategory) {
            $query->whereHas('serviceCategory', fn($q) => $q->where('name', $this->selectedServiceCategory));
        }

        if ($this->selectedStatus === 'active') {
            $query->where('is_active', true);
        } elseif ($this->selectedStatus === 'inactive') {
            $query->where('is_active', false);
        }

        $this->surveys = $query->get()->map(fn($survey) => [
            'survey_id' => $survey->survey_id,
            'title' => $survey->title,
            'description' => $survey->description,
            'is_active' => $survey->is_active ? 'active' : 'expired',
            'status' => $survey->is_active ? 'Active' : 'Expired',
            'category' => $survey->productCategory->name ?? $survey->serviceCategory->name ?? 'General',
            'submissions' => $survey->votes_count,
        ]);
    }

    public function render()
    {
        return view('components.cyber.explore-content');
    }
}
