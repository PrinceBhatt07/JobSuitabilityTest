<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;

use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class SubjectController extends Controller
{
    //
    public function addSubject(Request $request)
    {
        try {
            Subject::insert([
                'subject' => $request->subject
            ]);
            return response()->json(['success' => true, 'msg' => 'Subject Added Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function editSubject(Request $request)
    {
        try {
            $subject = Subject::find($request->id);
            $subject->subject = $request->subject;
            $subject->save();

            return response()->json(['success' => true, 'msg' => 'Subject Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteSubject(Request $request)
    {
        try {

            Subject::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Subject Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    //Add Questions in Subjects
    public function addQuestionInSubject(Request $request)
    {
        try {

            $subject = Subject::find($request->subject_id);
            $subject->questions()->sync($request->questions_ids);

            return response()->json(['success' => true,  'msg' => 'Question Added Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getSubjectQuestions(Request $request)
    {
        try {

            $data = Question::where('subject_id', $request->subject_id)->select('question', 'id')->get();

            $numofQues = count($data);

            return response()->json(['success' => true,  'data' => $data, 'numofQues' => $numofQues]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getQuestionsForSubject(Request $request)
    {

        try {

            $data = Question::with('subjects')->get();

            foreach ($data as $dataItem) {
                $dataItem['subject_id'] = $dataItem->subjects->pluck('id');
            }

            return response()->json(['success' => true,  'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function deleteSubjectQuestions(Request $request)
    {
        try {
            Question::where('id', $request->id)->update(['subject_id' => null]);

            return response()->json(['success' => true,  'msg' => 'Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getSubjects(Request $request)
    {
        $quesExamIds = Exam::find($request->exam_id)->questions()->pluck('questions.id')->toArray();

        $allQuestions = Question::with('subjects:id,subject')->get();
        $questions = '';
        foreach ($allQuestions as $item) {
            $subjects = '';

            if (filled($item->subjects) && $item->subjects[0]) {
                // dd($item->subjects[0]->subject);
                // foreach ($item->subjects as $subject) {
                //     $subjects .= ' ' . $subject->subject;
                // }
                $subjects = $item->subjects[0]->subject;
            }
            if (in_array($item->id, $quesExamIds)) {
                $questions .= "<tr>
                                <td><input type='checkbox' class='checkbox-question " .
                    $subjects
                    .
                    "' name='question_ids[]'  checked value=$item->id></td>
                                <td >$item->question</td>
                                <td><span class='badge text-light mr-2'>" . $subjects . "</span></td>
                            </tr>";
            } else {
                $questions .= "<tr>
                                    <td><input type='checkbox' class='checkbox-question " . $subjects . "' name='question_ids[]' value=$item->id></td>
                                    <td >$item->question</td>
                                    <td> <span class='badge text-light mr-2'>" . $subjects . "</span> </td>
                                </tr>
                ";
            }
        }



        $subject_id = Subject::where('exam_id', $request->exam_id)->pluck('id')->toArray();
        $subjects = Subject::select('id', 'subject', 'exam_id')->with('exam:id,exam_name')->get();

        $result = '';
        foreach ($subjects as $item) {

            // if (in_array($item->id, $subject_id)) {
            $result .= "<tr><td><input type='checkbox' class='checkbox-sub' name='subjects_ids[]' checked value=$item->id></td>
                <td>$item->subject</td>
                <td id='$item->subject'></td>
                </tr>";
            // } else {
            //     // $examName = $item->exam?->exam_name;
            //     $result .= "<tr><td><input type='checkbox' class='checkbox-sub' name='subjects_ids[]' value=$item->id></td>
            //     <td>$item->subject</td>
            //     <td id='$item->subject'></td>
            //     </tr>
            //     ";
            // }
        }
        $data = $subjects->map(function ($item) {
            return [
                'id' => $item->id,
                'subject' => $item->subject,
            ];
        });

        return response()->json(['data' => $data, 'result' => $result, 'questions' => $questions]);
    }

    public function addSubjects(Request $request)
    {
        try {
            Exam::find($request->exam_id)->questions()->sync($request->question_ids);


            // dd('done');
            // if (!filled($request->subjects_ids)) {
            //     Subject::where('exam_id', $request->exam_id)->update(['exam_id' => null]);
            // } else {
            //     Subject::where('exam_id', $request->exam_id)->update(['exam_id' => null]);

            //     Subject::whereIn('id', $request->subjects_ids)->update(['exam_id' => $request->exam_id]);
            // }

            return response()->json(['success' => true, 'msg' => ' Questions Added Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
