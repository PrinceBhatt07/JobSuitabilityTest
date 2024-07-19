<?php

namespace App\Http\Controllers;

use App\Imports\QnaImport;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    //
    public function qnaDashboard()
    {
        $questions = Question::orderBy('id', 'desc')->with('answers')->get();
        return view('Admin.qnaDashboard', ['questions' => $questions]);
    }

    public function qnaDashboardData()
    {
        $params = request()->get('params');

        $questions = Question::orderBy('id', 'desc')->with(['answers', 'subjects:id,subject']);

        if ($params == 'all') {
            $questions = $questions->get();
        } else {

            //many to many relation between subjects and questions
            $questions = $questions->whereHas('subjects', function ($query) use ($params) {
                $query->whereIn('subjects.id', explode(',', $params));
            })->get();
        }


        $subjects = Subject::all(['id', 'subject']);

        $subjectsMap = $subjects->map(function ($item) {
            return [
                'id' => $item->id,
                'subject' => $item->subject,
            ];
        });
        $tags = Tag::all(['id', 'tag_name']);
        // return response()->json(['success' => true, 'data' => $questions, $subjectsMap]);

        return response()->json(['success' => true, 'data' => $questions, 'subjects' => $subjectsMap, 'tags' => $tags]);
    }

    public function addQna(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'question' => 'required|unique:questions,question'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => 'Duplicate Questions are not allowed']);
            }

            $questionId = Question::insertGetId([
                'question' => $request->question
            ]);

            $question = Question::findOrFail($questionId);
            $question->subjects()->sync($request->tags);


            foreach ($request->answer as $answer) {

                $isCorrect = 0;
                if ($request->is_correct == $answer) {
                    $isCorrect = 1;
                }
                Answer::insert([
                    'question_id' => $questionId,
                    'answer' => htmlspecialchars(trim($answer), ENT_QUOTES, 'UTF-8'),
                    'is_correct' => $isCorrect
                ]);
            }

            return response()->json(['success' => true, 'msg' => 'Questions are successfully added']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getQnaDetails(Request $request)
    {
        $qna = Question::where('id', $request->qid)->with(['answers', 'subjects'])->get();
        return response()->json(['data' => $qna, 'tagIds' => $qna[0]->subjects->pluck('id')]);
    }


    public function deleteAnswer(Request $request)
    {
        Answer::where('id', $request->id)->delete();
        return response()->json(['success' => true, 'msg' => 'Answer Deleted Successfully']);
    }

    public function updateQna(Request $request)
    {

        try {
            $question = Question::where('id', $request->question_id)->first();
            $question->subjects()->sync($request->tags);

            $question->update([
                'question' => $request->editQuestion
            ]);



            // Old Answer update
            if (isset($request->answers)) {


                foreach ($request->answers as $key => $value) {
                    $is_correct = 0;
                    if ($request->is_correct == $value) {
                        $is_correct = 1;
                    }

                    $status = Answer::where('id', $key)->update([
                        'question_id' => $request->question_id,
                        'answer' => htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8'),
                        'is_correct' => $is_correct
                    ]);
                }
            }

            // New answer update
            if (isset($request->new_answer)) {
                foreach ($request->new_answer as $answer) {
                    $is_correct = 0;
                    if ($request->is_correct == $answer) {
                        $is_correct = 1;
                    }

                    Answer::insert([
                        'question_id' => $request->question_id,
                        'answer' => $answer,
                        'is_correct' => $is_correct,
                    ]);
                }
            }

            return response()->json(['success' => true, 'msg' => 'Question and Answer Updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteQna(Request $request)
    {

        try {
            $ids = is_array($request->id) ? $request->id : [$request->id];

            foreach ($ids as $id) {
                $questionModel = Question::find($id);
                $questionModel->answers()->delete();
                $questionModel->delete();
            }

            return response()->json(['success' => true, 'msg' => 'Question Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function importQna(Request $request)
    {
        try {
            Excel::import(new QnaImport, $request->file('file'));

            return response()->json(['success' => true, 'msg' => 'Import Qna Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getSub(Request $request)
    {
        try {
            $subject_id = Question::where('id', $request->question_id)->select('subject_id')->first();

            $subjects = Subject::all(['id', 'subject']);

            $data = $subjects->map(function ($item) {
                return [
                    'id' => $item->id,
                    'subject' => $item->subject,
                ];
            });

            return response()->json(['success' => true, 'data' => $data, 'subject_id' => $subject_id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function addSub(Request $request)
    {

        try {
            $subjectIds = $request->subjects_ids;
            foreach ($request->question_id as $question_id) {
                Question::find((int)$question_id)->subjects()->sync($subjectIds);
            }
            return response()->json(['success' => true, 'msg' => "Subject Added Successfully"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
