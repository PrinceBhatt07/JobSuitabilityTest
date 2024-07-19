<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\PasswordReset;
use App\Models\Subject;
use App\Models\User;
use App\Traits\AssignExams;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use AssignExams;
    public function loadRegister($exam_id = null)
    {

        // if (Auth::user() && Auth::user()->is_admin == 1) {
        //     return redirect('/admin/dashboard');
        // } elseif (Auth::user() && Auth::user()->is_admin == 0) {
        //     return redirect('/dashboard');
        // }

        $entrance_id = substr_replace($exam_id, "", -3);

        $exam = Exam::where('enterance_id', $entrance_id)->firstOrFail();

        if ($exam->date != date('Y-m-d') || $exam->from_time > Carbon::now()->format('H:i:s') || $exam->to_time < Carbon::now()->format('H:i:s')) {

            if ($exam->date != date('Y-m-d')) {
                $msg = 'This exam"s date is ' . $exam->date;
            } elseif ($exam->from_time > Carbon::now()->format('H:i:s')) {
                $msg = 'This exam will start at ' . $exam->from_time;
            } elseif ($exam->to_time < Carbon::now()->format('H:i:s')) {
                $msg = 'This exam ended at ' . $exam->to_time;
            }

            return view('404', ['message' => $msg]);
        } else {
            return view('register', ['exam_id' => $exam->id]);
        }
    }

    public function studentRegister(Request $request)
    {

        $request->validate([
            'name' => 'required|string|min:1',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'required|numeric|unique:users,contact|digits:10',
            'qualification' => 'required|max:30',
            'password' => 'required',
        ]);

        $qualification = $request->qualification[0] == 'Other' ?
            last($request->qualification) : $request->qualification[0];

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->phone;
        $user->qualification = $qualification;
        $user->password = Hash::make($request->password);
        $user->save();

        $msg = 'You are registered successfully. ';

        if (filled($request->exam_id)) {
            $examData = (object) [
                'user_id' => $user->id,
                'exams_ids' => [$request->exam_id],
                'attempts' => [1],
            ];

            $result = $this->examsAssign($examData);

            if ($result == 'noQuestions') {
                $msg .= 'No Questions in Exam';
            } elseif ($result == 'success') {
                $msg .= 'You can start your exam now.';
            } else {
                $msg .= 'Exam Could not be Assigned or Mail could not be Send';
            }
        }
        return redirect()->route('studentPh', ['studentPh' => $user->contact])->with('success', $msg);

        // return back()->with('success', $msg);
    }

    public function loadlogin()
    {
        if (Auth::user() && Auth::user()->is_admin == 1) {
            return redirect('/admin/dashboard');
        } elseif (Auth::user() && Auth::user()->is_admin == 0) {
            return redirect('/dashboard');
        }

        return view('login');
    }

    public function userlogin(Request $request)
    {
        $request->validate([
            'email' => 'string|required|max:100',
            'password' => 'string|required|min:6',
        ]);

        $userCredentials = $request->only('email', 'password');

        if (Auth::attempt($userCredentials)) {

            if (Auth::user()->is_admin == 1) {
                return redirect('/admin/dashboard');
            } else {
                $userPh = Auth::user()->contact;
                return redirect()->route('studentPh', ['studentPh' => $userPh]);
            }
        } else {
            return back()->with("error", "Useremail or password is incorrect");
        }
    }

    public function adminDashboard()
    {
        return view('Admin.adminDashboard');
    }

    public function adminDashboardData()
    {
        $subjects = Subject::orderBy('id', 'desc')->withCount('questions')->get()->toArray();
        return response()->json(['success' => true, 'data' => $subjects]);
    }

    public function studentDashboard()
    {
        $exams = Exam::orderBy('id', 'desc')->with('subjects')->orderBy('date')->get();
        return view('Student.studentDashboard', ['exams' => $exams]);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function forgetPasswordLoad()
    {
        return view('forgotPassword');
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user && $user->exists()) {
                $token = Str::random(40);
                $domain = URL::to('/');
                $url = $domain . '/reset-password?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = 'Password Reset';
                $data['body'] = 'Please click on the link below to reset your password';

                Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                if (Mail::failures()) {
                    return back()->with('error', 'Failed to send password reset email');
                }

                $dateTime = Carbon::now()->format('Y-m-d H:i:s');

                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $dateTime,
                    ]
                );

                return back()->with('success', 'Please check your mail to reset your password');
            } else {
                return back()->with('error', 'Email does not exist');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->first();

        if ($resetData && isset($resetData->token)) {
            $user = User::where('email', $resetData['email'])->first();

            if ($user && $user->exists()) {
                return view('resetPassword', compact('user'));
            }
        }

        return view('404');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'string|required|confirmed|min:6',
        ]);

        $user = User::find($request->id);

        if ($user && $user->exists()) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            PasswordReset::where('email', $user->email)->delete();

            return redirect('/login')->with('success', 'Password reset successful. Please login with your new password.');
        } else {
            return redirect('/login')->with('error', 'User not found or invalid request.');
        }
    }
}
