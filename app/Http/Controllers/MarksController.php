<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class MarksController extends Controller
{
    //
    public function loadMarks()
    {
        return view('Admin.marksDashboard');
    }
    public function loadMarksData()
    {
        $exams = Exam::orderBy('id', 'desc')->with('subjects')->withCount('questions')->get()->toArray();

        return response()->json(['success' => true, 'data' => $exams]);
    }

    public function editMarks(Request $request)
    {
        try {
            $totalNumberofQues = Exam::where('id', $request->exam_id)->withCount('questions')->pluck('questions_count')->first();
            $totalMarks = ($request->marks * $totalNumberofQues);
            $passingMarks = (($request->pass_marks / 100) * $totalMarks);

            $marks = Exam::find($request->exam_id);
            $marks->marks =  $totalMarks;
            $marks->passing_marks = $passingMarks;
            $marks->save();

            return response()->json(['success' => true, 'msg' => 'Marks Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
