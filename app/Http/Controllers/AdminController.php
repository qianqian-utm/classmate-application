<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class AdminController extends Controller
{
    // Subject functions
    public function createSubject(){
        $subjects = Subject::all();
        return view ('admin.createSubject', compact('subjects'));
    }

    public function storeSubject(Request $request){
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
        ]);

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->code = $request->code;
        $subject->save();

        return redirect()->route('admin.createSubject')->with('success', 'Subject created successfully');
    }

}


