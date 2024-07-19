<?php

namespace App\Traits;

use App\Models\Exam;
use App\Models\User;
use App\Mail\SendMail;
use App\Models\ExamLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

trait AssignExams
{
    public function examsAssign($data)
    {
        $user = User::find($data->user_id);
        $exams_ids = $data->exams_ids;
        $attempts = $data->attempts;

        // Filter out null attempts
        $newAttempt = array_filter($attempts, function ($attempt) {
            return $attempt !== null;
        });

        // Sync exams and attempts
        $syncData = [];
        foreach ($exams_ids as $index => $exam_id) {
            $syncData[$exam_id] = ['attempts' => $newAttempt[$index]];
        }
        $user->exams()->sync($syncData);

        // Check if there are no questions for any exam
        $qnaCount = Exam::whereIn('id', $exams_ids)->withCount('getQna')->get()->toArray();
        foreach ($qnaCount as $count) {
            if ($count['get_qna_count'] <= 0) {
                return 'noQuestions';
            }
        }

        return $this->sendMail($user->id, $exams_ids, $newAttempt);
    }


    public function sendMail($user_id, $exams_ids, $newAttempt)
    {
        try {
            $examData = Exam::whereIn('id', $exams_ids)->pluck('exam_name', 'enterance_id');
            $userId = Crypt::encrypt($user_id);
            $baseUrl = url('/');
            $studentName = User::where('id', $user_id)->select('email')->first();

            $urlArray = [];
            $i = 0;

            foreach ($examData as $enteranceId => $examName) {
                $urlArray[] = [
                    'name' => $examName,
                    'url' => $baseUrl . '/exam' . '/' . $userId . '/' . $enteranceId,
                ];

                $examLink = new ExamLink();

                $examLink->updateOrInsert(
                    [
                        'exam_id' => $exams_ids[$i],
                        'user_id' => Crypt::decrypt($userId),
                    ],
                    [
                        'examlink' => $baseUrl . '/exam' . '/' . $userId . '/' . $enteranceId,
                        'attempts' => $newAttempt[$i],
                        'remaining_attempts' => $newAttempt[$i],
                    ]
                );
                ++$i;
            }

            $mailData = [
                'title' => 'MVT Entrance Exam Link',
                'body' => 'Exam Links:',
                'urls' => $urlArray,
            ];

            Mail::to($studentName->email)->send(new SendMail($mailData));
            return 'success';
        } catch (\Exception $e) {
            Log::info($e);
            return 'failure';
        }
    }
}
