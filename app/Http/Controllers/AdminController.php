<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use App\Models\QnaExam;
use App\Models\Subject;
use App\Models\ExamLink;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    public function examDashboard()
    {
        return view('Admin.exam-dashboard');
    }

    public function examDashboardData()
    {
        $subjects = Subject::orderBy('id', 'desc')->withCount('questionsCount')->get()->toArray();
        $exams = Exam::orderBy('id', 'desc')->with('subjects')->withCount('questions')->get()->toArray();
        $idSubjectArray = [];
        foreach ($exams as $exam) {
            foreach ($exam['subjects'] as $subject) {
                $idSubjectArray[] = [
                    'subject_id' => $subject['id'],
                    'exam_id' => $subject['exam_id'],
                    'subject' => $subject['subject'],
                ];
            }
        }
        return response()->json(['success' => true, 'subjects' => $subjects, 'exams' => $exams, 'idSubjectArray' => $idSubjectArray]);
    }

    public function addExam(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'exam_name' => 'required|unique:exams,exam_name'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => 'Duplicate Exams are not allowed']);
            }

            $uniqe_id = uniqid('exid');
            Exam::create([
                'exam_name' => $request->exam_name,
                'date' => $request->date,
                'from_time' => Carbon::createFromFormat('h:i A', $request->from_time)->format('H:i:s'),
                'to_time' => Carbon::createFromFormat('h:i A', $request->to_time)->format('H:i:s'),
                'time' => $request->time,
                'attempt' => $request->attempt,
                'enterance_id' => $uniqe_id
            ]);

            return response()->json(['success' => true, 'msg' => ' Exam Added Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function getExamDetail($id)
    {
        try {
            $exams = Exam::find($id);
            return response()->json(['success' => true, 'data' => $exams]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function updateExam(Request $request)
    {

        try {
            $exams = Exam::find($request->exam_id);
            $exams->exam_name = $request->exam_name;
            $exams->date = $request->date;
            $exams->from_time = Carbon::createFromFormat('h:i A', $request->from_time)->format('H:i:s');
            $exams->to_time = Carbon::createFromFormat('h:i A', $request->to_time)->format('H:i:s');
            $exams->time = $request->time;
            $exams->save();
            return response()->json(['success' => true, 'msg' => 'Exam Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteExam(Request $request)
    {

        try {
            Exam::where('id', $request->exam_id)->delete();
            return response()->json(['success' => true, 'msg' => 'Exam Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }



    public function getQuestions(Request $request)
    {
        try {
            $questions = Question::all();
            $data = [];
            $counter = 0;

            if (count($questions) > 0) {
                foreach ($questions as $question) {
                    $qnaExam = QnaExam::where(['exam_id' => $request->exam_id, 'question_id' => $request->question_id])->get();

                    if (count($qnaExam) == 0) {
                        $data[$counter]['id'] = $question->id;
                        $data[$counter]['questions'] = $question->question;
                        $counter++;
                    }
                }
                return response()->json(['success' => true, 'msg' => 'Question data!', 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'msg' => "Questions not found"]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addQuestions(Request $request)
    {

        try {

            QnaExam::where('exam_id', $request->exam_id)->whereIn('question_id', $request->questions_ids)->delete();

            $dataToInsert = [];
            foreach ($request->questions_ids as $qid) {
                $dataToInsert[] = [
                    'exam_id' => $request->exam_id,
                    'question_id' => $qid
                ];
            }

            QnaExam::insert($dataToInsert);

            return response()->json(['success' => true, 'msg' => 'Question(s) added successfully']);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'msg' => 'An error occurred while processing your request']);
        }
    }

    public function getExamQuestions(Request $request)
    {
        try {
            $subjects = Subject::where('exam_id', $request->exam_id)->withCount('questions')->get();
            // dd($subjects);
            $data = $subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject' => $subject->subject,
                    'questionsCount' => $subject->questions_count,
                ];
            });

            return response()->json(['success' => true, 'msg' => 'Question(s) Details!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'An error occurred while processing your request']);
        }
    }

    public function deleteExamQuestions(Request $request)
    {
        try {

            Subject::where('id', $request->id)->update(['exam_id' => null]);
            return response()->json(['success' => true, 'msg' => 'Question Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'An error occurred while processing your request']);
        }
    }

    public function verifyUser($studentPh)
    {
        User::where('contact', $studentPh)->firstOrFail();
        return view('studentDashboard');
    }

    public function verifyUserData($studentPh)
    {
        $userInfo = User::where('contact', $studentPh)->select(['id', 'name'])->first()->toArray();
        $userExamData = ExamLink::where('user_id', $userInfo['id'])->select(['examlink', 'exam_id', 'attempts', 'remaining_attempts'])->with('exams')->get()->toArray();

        return response()->json(['success' => true, 'userExamData' => $userExamData, 'userInfo' => $userInfo]);
    }
}
