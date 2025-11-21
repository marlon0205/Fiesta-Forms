<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerOptions extends Model {
    protected $primaryKey = 'option_id';

    public function question() {
        return $this->belongsTo(Questions::class, 'question_id', 'question_id');
    }

    public function votes() {
        return $this->hasMany(Votes::class, 'option_id', 'option_id');
    }
}
