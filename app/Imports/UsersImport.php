<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Hash;
use Illuminate\Support\Facades\Hash as FacadesHash;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)

    {

        $user = User::where('email', $row['email'])->orWhere('contact', $row['contact'])->get();

        if (!filled($user)) {
            return new User([
                'name'     => $row['name'],
                'email'    => $row['email'],
                'contact'  => $row['contact'],
                'qualification'  => $row['qualification'] ?? null,
            ]);
        }
    }
}
