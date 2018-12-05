<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;

class UsersViewExport implements FromView
{
    public function view(): View
    {
        $user_data = DB::table('users')->get();
        return view('export_excel', [
            'user_data' => $user_data
        ]);
    }
}
