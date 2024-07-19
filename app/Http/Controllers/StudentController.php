<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{

    public function studentDashboard()
    {
        $students = User::where('is_admin', 0)->paginate(10);
        return view('Admin.studentDashboard', ['students' => $students]);
    }

    public function studentDashboardData(Request $request)
    {

        $search = $request->get('search');

        if (filled($search)) {
            $students = User::orderBy('id', 'desc')->where('email', $search)
                ->paginate(10);

            return response()->json(['success' => true, 'students' => $students]);
        } else {
            $students = User::orderBy('id', 'desc')->where('is_admin', 0)->paginate(10);
            $subjects = Exam::all(['id', 'exam_name']);
            $data = $subjects->map(function ($item) {
                return [
                    'id' => $item->id,
                    'exam_name' => $item->exam_name,
                ];
            });
            return response()->json(['success' => true, 'jsonData' => $data, 'students' => $students]);
        }
    }


    public function addStudent(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'contact' => [
                    'required',
                    'unique:users,contact',
                    'regex:/^[^\s\/]+$/',
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => $validator->errors()]);
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact
            ]);

            return response()->json(['success' => true, 'msg' => 'User Added Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function editStudent(Request $request)
    {
        try {

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact = $request->contact;
            $user->save();
            return response()->json(['success' => true, 'msg' => 'Student Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    public function deleteStudent(Request $request)
    {
        try {
            User::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Student Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
