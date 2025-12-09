<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory; // KORREKTUR: Trait hinzugefügt

    protected $primaryKey = 'question_id';

    protected $fillable = ['question_text', 'survey_id'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'survey_id');
    }

    public function answerOptions()
    {
        return $this->hasMany(AnswerOptions::class, 'question_id', 'question_id');
    }

    public function votes()
    {
        return $this->hasMany(Votes::class, 'question_id', 'question_id');
    }
}
