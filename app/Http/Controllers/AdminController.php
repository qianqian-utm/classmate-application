<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\ClassDetail;
use App\Models\AssignmentDetail;
use App\Models\ExamDetail;

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

    public function notification(){
        $classDetails = ClassDetail::with('subject')->orderBy('created_at', 'desc')->get();
        $assignmentDetails = AssignmentDetail::with('subject')->orderBy('created_at', 'desc')->get();
        $examDetails = ExamDetail::orderBy('created_at', 'desc')->get();
        return view ('admin.notification', compact('classDetails', 'assignmentDetails', 'examDetails'));
    }

//    NOTIFICATIONS FUNCTION
    public function classNotification(){
        $subjects = Subject::all();
        return view ('/admin/notification/class', compact('subjects'));
    }

    public function storeClassNotification(Request $request)
    {
    $request->validate([
        'subject_id' => 'required|exists:subjects,id',
        'title' => 'required|string',
        'date' => 'nullable|date',
        'description' => 'required|string',
    ]);

    ClassDetail::create($request->only(['subject_id', 'title','date', 'description']));

    return redirect()->route('admin.notification')->with('success', 'Class detail added successfully.');
    }

    public function editClassNotification($id)
    {
    $classDetail = ClassDetail::find($id);
    if ($classDetail) {
        $subjects = Subject::all();
        return view('admin.notification.editClass', compact('classDetail', 'subjects'));
    }
    }

    public function updateClassNotification(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string',
            'date' => 'nullable|date', 
            'description' => 'required|string',
        ]);

        $classDetail = ClassDetail::find($id);
        if ($classDetail) {
            $classDetail->update($request->only(['subject_id', 'title',  'date', 'description']));
            return redirect()->route('admin.notification')->with('success', 'Class detail updated successfully.');
        }
    }

    public function deleteClassNotification($id)
    {
        $classDetail = ClassDetail::find($id);
        if ($classDetail) {
            $classDetail->delete();
            return redirect()->route('admin.notification')->with('success', 'Class detail deleted successfully.');
        }
        return redirect()->route('admin.notification')->with('error', 'Class detail not found.');
    }

    public function assignmentNotification(){
        $subjects = Subject::all();
        return view ('/admin/notification/assignment', compact('subjects'));
    }

    public function storeAssignmentNotification(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            
        ]);

        AssignmentDetail::create($request->only(['subject_id', 'title', 'description','due_date']));

        return redirect()->route('admin.notification')->with('success', 'Assignment detail added successfully.');
    }

    public function editAssignmentNotification($id)
    {
        $assignmentDetail = AssignmentDetail::find($id);
        if ($assignmentDetail) {
            $subjects = Subject::all();
            return view('admin.notification.editAssignment', compact('assignmentDetail', 'subjects'));
        }
    }

    public function updateAssignmentNotification(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'due_date' => 'nullable|date', 
        ]);

        $assignmentDetail = AssignmentDetail::find($id);
        if ($assignmentDetail) {
            $assignmentDetail->update($request->only(['subject_id', 'title', 'description','due_date']));
            return redirect()->route('admin.notification')->with('success', 'Assignment detail updated successfully.');
        }
    }

    public function deleteAssignmentNotification($id)
    {
        $assignmentDetail = AssignmentDetail::find($id);
        if ($assignmentDetail) {
            $assignmentDetail->delete();
            return redirect()->route('admin.notification')->with('success', 'Assignment detail deleted successfully.');
        }
        return redirect()->route('admin.notification')->with('error', 'Class detail not found.');
    }

    public function examNotification(){
        //  $exams = ExamDetail::all();
        return view ('/admin/notification/exam');
    }

    public function storeExamNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]
        
        );
        ExamDetail::create($request->only([ 'title', 'description']));
        return redirect()->route('admin.notification')->with('success', 'Exam detail added successfully.');
    }

    public function editExamNotification($id)
    {
        $examDetails = ExamDetail::find($id);
        return view('admin/notification/editExam', compact('examDetails'));
    }

    public function updateExamNotification(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        // Find the ExamDetail by ID
        $examDetails = ExamDetail::find($id);

        // Check if the ExamDetail exists
        if (!$examDetails) {
            return redirect()->route('admin.notification')->with('error', 'Exam detail not found.');
        }

        // Update the ExamDetail with validated data
        $examDetails->update($request->only(['title', 'description']));

        // Redirect with success message
        return redirect()->route('admin.notification')->with('success', 'Exam detail updated successfully.');
    }

    public function deleteExamNotification($id)
    {
        $examDetails = ExamDetail::find($id);
        if ($examDetails) {
            $examDetails->delete();
            return redirect()->route('admin.notification')->with('success', 'Exam detail deleted successfully.');
        }
        return redirect()->route('admin.notification')->with('error', 'Class detail not found.');
    }

}


