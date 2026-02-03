<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteAnswers extends Model {
    protected $primaryKey = 'vote_answer_id';
    public $timestamps = false;

    protected $fillable = ['vote_id', 'question_id', 'option_id'];

    public function vote() {
        return $this->belongsTo(Votes::class, 'vote_id', 'vote_id');
    }

    public function question() {
        return $this->belongsTo(Questions::class, 'question_id', 'question_id');
    }

    public function answerOption() {
        return $this->belongsTo(AnswerOptions::class, 'option_id', 'option_id');
    }
}
