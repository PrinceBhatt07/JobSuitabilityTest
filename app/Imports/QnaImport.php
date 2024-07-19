<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;


class QnaImport implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $sanitizedQ = htmlspecialchars(trim($row[0]), ENT_QUOTES, 'UTF-8');
        $question = Question::where('question', $sanitizedQ)->first();

        if (filled($row[0]) && $question == null) {

            $questionId = Question::insertGetId([
                'question' => $sanitizedQ,
                'subject_id' => $row[1] ?? null
            ]);


            for ($i = 2; $i < count($row) - 1; $i++) {

                if ($row[$i] != null) {
                    $is_correct = 0;

                    // Check if $row[$i] index exists and it's not empty
                    if (isset($row[8]) && $row[8] == $row[$i]) {
                        $is_correct = 1;
                    }

                    Answer::insert([
                        'question_id' => $questionId,
                        'answer' => htmlspecialchars(trim($row[$i]), ENT_QUOTES, 'UTF-8'),
                        'is_correct' => $is_correct
                    ]);
                }
            }
        }
    }
}
