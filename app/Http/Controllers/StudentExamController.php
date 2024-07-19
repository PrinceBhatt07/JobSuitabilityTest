<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamUser;
use App\Traits\AssignExams;


class StudentExamController extends Controller
{
    use AssignExams;
    public function loadStudentExam()
    {
        return view('Admin.student-exam');
    }

    public function loadStudentExamData()
    {
        $userData = User::orderBy('id', 'desc')->where('is_admin', 0)->with('exams')->paginate(10);
        $filteredUserData = $userData->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        });

        $examData = Exam::pluck('exam_name', 'id');
        $examdata = $examData->map(function ($examName, $examId) {
            return [
                'id' => $examId,
                'name' => $examName,
            ];
        });
        return response()->json(['success' => true, 'userdata' => $userData, 'examData' => $examData]);
    }

    public function getExams()
    {
        try {
            $examData = Exam::all(['id', 'exam_name']);
            $examdata = $examData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->exam_name,
                ];
            });
            return response()->json(['success' => true,  'data' => $examdata]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function assignExams(Request $request)
    {
        $result = $this->examsAssign($request);
        if ($result == 'noQuestions') {
            return response()->json(['success' => false, 'msg' => 'Add Questions in Exam Before Assigning it']);
        } elseif ($result == 'success') {
            return response()->json(['success' => true, 'msg' => 'Exam Assigned']);
        } else {
            return response()->json(['success' => false, 'msg' => 'Exam Could not be Assigned']);
        }
    }



    public function getExamDetails(Request $request)
    {
        ExamUser::where('user_id', $request->user_id)->select(['created_at', 'attempts', 'exam_id']);
        try {
            $user = User::where('id', $request->user_id)->with('exams')->first()->toArray();

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User not found']);
            }
            $examInfoArray = [];

            for ($i = 0; $i < count($user['exams']); $i++) {
                $examInfo = [
                    'exam_name' => $user['exams'][$i]['exam_name'],
                    'mailSent' => $user['exams'][$i]['pivot']['created_at'],
                    'attempt' => $user['exams'][$i]['pivot']['attempts']
                ];
                $examInfoArray[] = $examInfo;
            }
            return response()->json(['success' => true, 'data' => $examInfoArray]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
