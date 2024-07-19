<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //
    public function loadReview()
    {
        return view('Admin.exam-reviewDashboard');
    }

    public function loadReviewData()
    {
        $attempts = ExamAttempt::with('user', 'exam')->orderBy('id','desc')->get();
        return response()->json(['success' => true, 'data' => $attempts]);
    }

    public function reviewQna(Request $request)
    {

        try {

            $attemptData = ExamAnswer::where('attempt_id', $request->attempt_id)->with(['question', 'answers'])->get();

            return response()->json(['success' => true, 'data' => $attemptData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function approveQna(Request $request)
    {
        try {
            $attemptId = $request->attempt_id;
            $examData = ExamAttempt::where('id', $attemptId)->with('user', 'exam')->get();
            $examId = ExamAttempt::where('id', $attemptId)->select('exam_id')->first()->toArray();
            $numOfQues = Exam::where('id', $examId['exam_id'])->withCount('getQna')->pluck('get_qna_count')->first();
            $marks = $examData[0]['exam']['marks'] / $numOfQues;
            $attemptData = ExamAnswer::where('attempt_id', $attemptId)->with('answers')->get();
            $totalMarks = 0;
            if (count($attemptData) > 0) {
                foreach ($attemptData as $data) {

                    if ($data?->answers?->is_correct == 1) {
                        $totalMarks += $marks;
                    }
                }
            }
            ExamAttempt::where('id', $attemptId)->update([
                'status' => 1,
                'marks' => $totalMarks
            ]);

            return response()->json(['success' => true,  'msg' => 'Approved Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
