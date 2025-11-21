<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service_Categories extends Model {
    protected $table = 'service__categories';
    protected $primaryKey = 'service_category_id';

    public function surveys() {
        return $this->hasMany(Survey::class, 'service_category_id', 'service_category_id');
    }
}
