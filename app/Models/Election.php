<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Election extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
    ];

    public function candidates()
    {
        return $this->belongsToMany(User::class, 'election_candidates');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
