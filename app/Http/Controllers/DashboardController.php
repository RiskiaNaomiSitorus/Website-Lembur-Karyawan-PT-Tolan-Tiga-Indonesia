<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the count of registered karyawan
        $karyawanCount = Karyawan::count();

        // Get the count of registered users
        $userCount = User::count();

        
        // Fetch paginated users data for the table, ordered by latest join date
        $users = User::orderBy('updated_at', 'desc')->paginate(10); // Adjust the number 10 to the desired number of users per page

        // Pass the counts and users to the view
        return view('app.Dashboard', compact('karyawanCount', 'userCount', 'users'));
    }
}
