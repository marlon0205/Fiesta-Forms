<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerOptions extends Model {
    use HasFactory;

    protected $primaryKey = 'option_id';

    protected $fillable = ['option_text', 'question_id'];

    public function question() {
        return $this->belongsTo(Questions::class, 'question_id', 'question_id');
    }

    public function votes() {
        return $this->hasMany(Votes::class, 'option_id', 'option_id');
    }
}
