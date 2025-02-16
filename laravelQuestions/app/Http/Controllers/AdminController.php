<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // dd("You have admin access!");
        return view('admin.dashboard'); // Ensure this view exists in `resources/views/admin/dashboard.blade.php`
    }
}

