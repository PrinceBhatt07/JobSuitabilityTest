<?php

use Database\Seeders\AdminSeeder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MarksController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RemarksController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentExamController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    abort(404);
});

Route::get('/register/{exam_id?}', [AuthController::class, 'loadRegister']);

Route::post('/register', [AuthController::class, 'studentRegister'])->name("studentRegister");

Route::get('/login', [AuthController::class, 'loadlogin']);
Route::post('/login', [AuthController::class, 'userlogin'])->name('userlogin');
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/forgotPassword', [AuthController::class, 'forgetPasswordLoad']);
Route::post('/forgotPassword', [AuthController::class, 'forgetPassword'])->name('forgotPassword');
Route::get('/reset-password', [AuthController::class, 'resetPasswordLoad']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::group(['middleware' => ['web', 'checkAdmin']], function () {
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('adminDashboard');
    Route::get('/admin/dashboardData', [AuthController::class, 'adminDashboardData'])->name('adminDashboardData');

    //subject
    Route::post('/add-Subject', [SubjectController::class, 'addSubject'])->name('addSubject');
    Route::post('/edit-Subject', [SubjectController::class, 'editSubject'])->name('editSubject');
    Route::post('/delete-Subject', [SubjectController::class, 'deleteSubject'])->name('deleteSubject');
    Route::post('/add-question-inSubject', [SubjectController::class, 'addQuestionInSubject'])->name('addQuestionsInSubject');
    Route::get('/get-subject-question', [SubjectController::class, 'getSubjectQuestions'])->name('getSubjectQuestions');
    Route::get('/get-question', [SubjectController::class, 'getQuestionsForSubject'])->name('getQuestionsForSubject');
    Route::get('/delete-subjectQues', [SubjectController::class, 'deleteSubjectQuestions'])->name('deleteSubjectQuestions');
    Route::get('/get-Subjects', [SubjectController::class, 'getSubjects'])->name('getSubjects');
    Route::post('/add-subjects', [SubjectController::class, 'addSubjects'])->name('addSubjects');

    //Exam
    Route::get('/admin/exam', [AdminController::class, 'examDashboard']);
    Route::get('/admin/examdata', [AdminController::class, 'examDashboardData'])->name('examDashboardData');
    Route::post('/add-exam', [AdminController::class, 'addExam'])->name('addExam');
    Route::get('/get-exam-detail/{id}', [AdminController::class, 'getExamDetail'])->name('getExamDetail');
    Route::post('/update-exam', [AdminController::class, 'updateExam'])->name('updateExam');
    Route::post('/delete-exam', [AdminController::class, 'deleteExam'])->name('deleteExam');

    //Q$A Routes
    Route::get('/admin/qna-ans', [QuestionController::class, 'qnaDashboard']);
    Route::get('/admin/qna-ans-data', [QuestionController::class, 'qnaDashboardData'])->name('qnaDashboardData');
    Route::post('/add-qna-ans', [QuestionController::class, 'addQna'])->name('addQna');
    Route::get('/get-qna-details', [QuestionController::class, 'getQnaDetails'])->name('getQnaDetails');
    Route::get('/delete-ans', [QuestionController::class, 'deleteAnswer'])->name('deleteAnswer');
    Route::post('/update-qna-ans', [QuestionController::class, 'updateQna'])->name('updateQna');
    Route::post('/delete-qna-ans', [QuestionController::class, 'deleteQna'])->name('deleteQna');
    Route::post('/import-qna-ans', [QuestionController::class, 'importQna'])->name('importQna');
    Route::get('/admin-getSub', [QuestionController::class, 'getSub'])->name('getSub');
    Route::post('/admin/addSub', [QuestionController::class, 'addSub'])->name('addSub');

    //Tags Routes
    Route::get('/admin/tags', [TagController::class, 'tagDashboard']);
    Route::get('/admin/tagsdata', [TagController::class, 'tagDashboardData'])->name('tagDashboardData');
    Route::post('/add-tag', [TagController::class, 'addTag'])->name('addTag');
    // Route::post('/edit-tag', [TagController::class, 'editTag'])->name('editTag');
    Route::post('/delete-tag', [TagController::class, 'deleteTag'])->name('deleteTag');

    //Students Routes
    Route::get('/admin/students', [StudentController::class, 'studentDashboard']);
    Route::get('/admin/studentsData', [StudentController::class, 'studentDashboardData'])->name('studentDashboardData');
    Route::post('/add-student', [StudentController::class, 'addStudent'])->name('addStudent');
    Route::post('/edit-student', [StudentController::class, 'editStudent'])->name('editStudent');
    Route::post('/delete-student', [StudentController::class, 'deleteStudent'])->name('deleteStudent');


    //qna exams Routing
    Route::get('/get-questions', [AdminController::class, 'getQuestions'])->name('getQuestions');
    Route::post('/add-questions', [AdminController::class, 'addQuestions'])->name('addQuestions');
    Route::get('/get-exam-questions', [AdminController::class, 'getExamQuestions'])->name('getExamQuestions');
    Route::get('/delete-exam-questions', [AdminController::class, 'deleteExamQuestions'])->name('deleteExamQuestions');




    //Marks routes
    Route::get('/admin/marks', [MarksController::class, 'loadMarks']);
    Route::get('/admin/marksdata', [MarksController::class, 'loadMarksData'])->name('loadMarksData');
    Route::post('/admin/editMarks', [MarksController::class, 'editMarks'])->name('editMarks');

    //exam revieW routes
    Route::get('/admin/review', [ReviewController::class, 'loadReview']);
    Route::get('/admin/reviewdata', [ReviewController::class, 'loadReviewData'])->name('loadReviewData');
    Route::get('/admin/get-reviewed-qna', [ReviewController::class, 'reviewQna'])->name('reviewQna');
    Route::post('/admin/approve-qna', [ReviewController::class, 'approveQna'])->name('approveQna');

    //remarks routes
    Route::get('/admin/remarks', [RemarksController::class, 'loadRemarks']);
    Route::get('/admin/remarksdata', [RemarksController::class, 'loadRemarksData'])->name('loadRemarksData');
    Route::post('/admin/addremarks', [RemarksController::class, 'addRemarks'])->name('addRemarks');
    Route::get('/admin/getRemarks', [RemarksController::class, 'getRemarks'])->name('getRemarks');

    //Student Exams
    Route::get('/admin/students-exam', [StudentExamController::class, 'loadStudentExam']);
    Route::get('/admin/students-examdata', [StudentExamController::class, 'loadStudentExamData'])->name('loadStudentExamData');
    Route::get('/admin/get-Exams', [StudentExamController::class, 'getExams'])->name('getExams');
    Route::post('/admin/assignExams', [StudentExamController::class, 'assignExams'])->name('assignExams');
    Route::get('/admin-get-examDetails', [StudentExamController::class, 'getExamDetails'])->name('getExamDetails');
});

Route::get('/exam/{studentId}/{examId}', [ExamController::class, 'loadExamDashboard']);
Route::post('/exam-submit', [ExamController::class, 'examSubmit'])->name('examSubmit');
Route::get('/results', [ExamController::class, 'resultDashboard'])->name('resultDashboard');


Route::controller(UserController::class)->group(function () {
    Route::get('users', 'index');
    Route::get('users-export', 'export')->name('users.export');
    Route::post('users-import', 'import')->name('users.import');
});

Route::get('/{studentPh}', [AdminController::class, 'verifyUser'])->name('studentPh');
Route::post('/{studentPh}', [AdminController::class, 'verifyUserData'])->name('verifyUserData');
