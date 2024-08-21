<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['vote_id', 'question_id', 'answer'];

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
