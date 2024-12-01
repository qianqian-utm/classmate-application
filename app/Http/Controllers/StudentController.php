<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        // Get the authenticated student's data
        if(auth()->check()){
            switch(auth()->user()->role){
                case "1":
                    return redirect()->route('home');
                    break;
                case "2":
                    return redirect()->route('lecturer.index');
                    break;
                case "3":
                    $student = auth()->user();
                    return view('Student.index', compact('student'));
                    break;
            }
        }
    }

    public function notifications()
    {
        // Get the authenticated student's data
        
        return view('Student.notifications');
    }
}