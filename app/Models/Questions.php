<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model {
    protected $primaryKey = 'question_id';

    public function survey() {
        return $this->belongsTo(Survey::class, 'survey_id', 'survey_id');
    }

    public function answerOptions() {
        return $this->hasMany(AnswerOptions::class, 'question_id', 'question_id');
    }

    public function votes() {
        return $this->hasMany(Votes::class, 'question_id', 'question_id');
    }
}
