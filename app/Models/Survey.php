<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory; // KORREKTUR: Trait hinzugefügt

    protected $primaryKey = 'survey_id';

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'service_category_id',
        'product_category_id',
        'duration_days',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function serviceCategory()
    {
        return $this->belongsTo(Service_Categories::class, 'service_category_id', 'service_category_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(Product_Categories::class, 'product_category_id', 'product_category_id');
    }

    public function questions()
    {
        return $this->hasMany(Questions::class, 'survey_id', 'survey_id');
    }

    /**
     * Gibt den Namen der Spalte zurück, die für das Route Model Binding verwendet wird.
     */
    public function getRouteKeyName()
    {
        return 'survey_id';
    }
}
