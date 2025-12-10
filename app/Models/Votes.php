<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Votes extends Model {
    protected $primaryKey = 'vote_id';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function question() {
        return $this->belongsTo(Questions::class, 'question_id', 'question_id');
    }

    public function answerOption() {
        return $this->belongsTo(AnswerOptions::class, 'option_id', 'option_id');
    }
}
