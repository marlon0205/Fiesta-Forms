<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Categories extends Model {
    protected $table = 'product__categories';
    protected $primaryKey = 'product_category_id';

    public function surveys() {
        return $this->hasMany(Survey::class, 'product_category_id', 'product_category_id');
    }
}
