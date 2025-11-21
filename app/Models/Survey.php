<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model {
    protected $primaryKey = 'survey_id';

    // 1. belongsTo Beziehungen

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function serviceCategory() {
        return $this->belongsTo(Service_Categories::class, 'service_category_id', 'service_category_id');
    }

    public function productCategory() {
        return $this->belongsTo(Product_Categories::class, 'product_category_id', 'product_category_id');
    }

    // 2. hasMany Beziehung zu Questions
    public function questions() {
        return $this->hasMany(Questions::class, 'survey_id', 'survey_id');
    }
}
