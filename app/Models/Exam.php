<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExamAttempt;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Exam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_name',
        'subject_id',
        'date',
        'from_time',
        'to_time',
        'time',
        'marks',
        'passing_marks',
        'enterance_id'
    ];

    protected $appends = ['attempt_counter'];
    public $count = '';

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'exam_id', 'id');
    }
    public function getQnaExam()
    {
        return $this->hasMany(QnaExam::class, 'exam_id', 'id');
    }
    // public function getIdAttribute($value)
    // {
    //     $attemptCounter = ExamAttempt::where(['exam_id' => $value, 'user_id' => auth()->user()->id])->count();
    //     $this->count = $attemptCounter;
    //     return $value;
    // }
    public function getAttemptCounterAttribute()
    {
        return $this->count;
    }

    public function getQna()
    {
        return $this->belongsToMany(Question::class);
        // return $this->hasManyThrough(Question::class, Subject::class, 'exam_id', 'subject_id', 'id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(Exam::class, 'exam_users', 'exam_id', 'user_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}
