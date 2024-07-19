<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject'
    ];



    public function questions()
    {
        return $this->belongsToMany(Question::class)->withTimestamps();
    }

    public function questionsCount()
    {
        return $this->hasMany(Question::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
