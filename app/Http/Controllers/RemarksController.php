<?php

namespace App\Http\Controllers;

use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class RemarksController extends Controller
{
    //
    public function loadRemarks()
    {
        return view('Admin.remarks-dashboard');
    }

    public function loadRemarksData(){
        $studentRemarks = ExamAttempt::orderBy('id', 'desc')->with('exam', 'user')->get()->toArray();
        return response()->json(['success' => true, 'data'=>$studentRemarks]);
    }

    public function addRemarks(Request $request)
    {
        try {
            ExamAttempt::where('id', $request->remarksId)->update(['remarks' => $request->remarks]);
            return response()->json(['success' => true,  'msg' => 'Remarks Added Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getRemarks(Request $request)
    {
        try {
            $remarks = ExamAttempt::where('id', $request->attempt_id)->pluck('remarks')->first();
            return response()->json(['success' => true, 'data' => $remarks]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
