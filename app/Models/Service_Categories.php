<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_Categories extends Model
{
    use HasFactory; // KORREKTUR: Trait hinzugefügt

    protected $table = 'service__categories';
    protected $primaryKey = 'service_category_id';
    protected $fillable = ['name'];

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'service_category_id', 'service_category_id');
    }
}
