<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'exam_id',
        'examlink',
        'attempts',
        'remaining_attempts'
    ];

    public function exams(){
        return $this->belongsTo(Exam::class,'exam_id','id');
    }

}
