<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $primaryKey = 'reward_id';

    protected $fillable = [
        'name',
        'description',
        'points_required',
    ];
}
