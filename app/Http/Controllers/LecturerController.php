<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LecturerController extends Controller
{
    public function index()
    {
        if(auth()->check()){
            switch(auth()->user()->role){
                case "1":
                    return redirect()->route('home');
                    break;
                case "2":
                    $lecturer = auth()->user();
                    return view('Lecturer.index', compact('lecturer'));
                    break;
                case "3":
                    return redirect()->route('student.index');
                    break;
            }
        }
    }

}