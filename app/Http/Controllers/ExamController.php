<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use App\Models\Answer;
use App\Models\QnaExam;
use App\Models\ExamLink;
use App\Models\Question;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Session as FacadesSession;

class ExamController extends Controller
{


    public function loadExamDashboard($studentId, $examId)
    {
        try {
            $studentId = Crypt::decrypt($studentId);
            $qnaExam = Exam::where('enterance_id', $examId)->with('getQna')->get()->toArray();
            $examinationId = Exam::where('enterance_id', $examId)->first()->id;
            $attemptcounter = ExamLink::where(['exam_id' => $examinationId, 'user_id' => $studentId])->orderBy('id', 'desc')->first();


            // if($qnaExam[0]['id'] != 0){
            if ($qnaExam[0]['date'] > date('Y-m-d')) {
                return view('Student.exam-Dashboard', ['success' => false, 'msg' => 'This Exam will be start on' . " " .  $qnaExam[0]['date'], 'exam' => $qnaExam]);
            }

            if ($qnaExam[0]['date'] < date('Y-m-d')) {
                return view('Student.exam-Dashboard', ['success' => false, 'msg' => 'This Exam has been expired on' . " " . $qnaExam[0]['date'], 'exam' => $qnaExam]);
            }



            if ($qnaExam[0]['from_time'] > Carbon::now()->format('H:i:s')) {
                return view('Student.exam-Dashboard', ['success' => false, 'msg' => 'This exam"s start time is ' . " " .  $qnaExam[0]['from_time'], 'exam' => $qnaExam]);
            }

            if ($qnaExam[0]['to_time'] < Carbon::now()->format('H:i:s')) {
                return view('Student.exam-Dashboard', ['success' => false, 'msg' => 'This exam"s end time is ' . " " . $qnaExam[0]['to_time'], 'exam' => $qnaExam]);
            }
            // }


            if ($attemptcounter->remaining_attempts <= 0) {
                return view('Student.exam-Dashboard', ['success' => false, 'msg' => 'No more attempts are permitted for this exam!', 'exam' => $qnaExam]);
            }
            $attemptcounter->update(['remaining_attempts' => $attemptcounter->remaining_attempts - 1]);
            if (count($qnaExam) > 0) {

                $attemptCounter = ExamAttempt::where(['exam_id' => $qnaExam[0]['id'], 'user_id' => $studentId])->count();

                if (count($qnaExam[0]['get_qna']) > 0) {
                    $getQnaIds = array_column($qnaExam[0]['get_qna'], 'id');
                    $questions = [];
                    foreach ($qnaExam[0]['get_qna'] as $questionData) {
                        $questions[] = [
                            'id' => $questionData['id'],
                            'question' => $questionData['question'],
                        ];
                    }
                    foreach ($questions as $item) {
                        $ids[] = $item['id'];
                    }
                    $questions = Question::whereIn('id', $ids)->with('answers')->get()->toArray();
                    return view('Student.exam-Dashboard', ['success' => true, 'exam' => $qnaExam, 'questions' => $questions, 'student_id' => $studentId]);
                }
            } else {
                return view('404');
            }
        } catch (\Exception $e) {
            session()->flash('message', 'Your Link is Invalid.');
            return view('404')->with('message', 'Your Link is Invalid.');
        }
    }

    public function examSubmit(Request $request)
    {
        $totalMarks = Exam::where('id', $request->exam_id)->pluck('marks')->first();

        $attempt_id = ExamAttempt::insertGetId([
            'exam_id' => $request->exam_id,
            'user_id' => $request->student_id,
            'total_marks' => $totalMarks
        ]);

        $qCount = count($request->q);

        for ($i = 0; $i < $qCount; $i++) {
            // if (!empty(request()->input('ans_' . ($i + 1)))) {
            ExamAnswer::insert([
                'attempt_id' => $attempt_id,
                'question_id' => $request->q[$i],
                'answer_id' => request()->input('ans_' . ($i + 1)) ?? null
            ]);
            // }
        }
        $name = User::where('id', $request->student_id)->pluck('name')->first();
        return view('thank-you', ['name' => $name]);
    }


    public function resultDashboard()
    {
        $attempts = ExamAttempt::where('user_id', Auth()->user()->id)->with('exam')->orderBy('updated_at')->get();
        return view('Student.reviewDashboard', compact('attempts'));
    }
}
