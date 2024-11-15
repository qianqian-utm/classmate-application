<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        // Get the authenticated student's data
        $student = auth()->user();
        return view('Student.index', compact('student'));
    }

    public function notifications()
    {
        // Get the authenticated student's data
        
        return view('Student.notifications');
    }
}