<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LecturerController extends Controller
{
    public function index()
    {
        // Get the authenticated lecturer's data
        $lecturer = auth()->user();
        return view('Lecturer.index', compact('lecturer'));
    }

}