<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Votes extends Model {
    protected $primaryKey = 'vote_id';

    protected $fillable = ['survey_id', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function survey() {
        return $this->belongsTo(Survey::class, 'survey_id', 'survey_id');
    }

    public function answers() {
        return $this->hasMany(VoteAnswers::class, 'vote_id', 'vote_id');
    }
}
