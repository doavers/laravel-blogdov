<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExportExcelController extends Controller
{
    public function index()
    {
        $user_data = DB::table('users')->get();
        return view('export_excel')->with('user_data', $user_data);
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function excel()
    {
        $user_data = DB::table('users')->get()->toArray();
        $user_array[] = array('User Name', 'Email', 'Created At', 'Updated At', 'Bio');
        foreach ($user_data as $user) {
            $user_array[] = array(
                'User Name'  => $user->name,
                'Email'      => $user->email,
                'Created At' => $user->created_at,
                'Updated At' => $user->updated_at,
                'Bio'        => $user->bio,
            );
        }
        Excel::create('User Data', function ($excel) use ($user_array) {
            $excel->setTitle('User Data');
            $excel->sheet('User Data', function ($sheet) use ($user_array) {
                $sheet->fromArray($user_array, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
