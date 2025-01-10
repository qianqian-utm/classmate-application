<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.chat');
    }

    public function fetchGroqResponse($question, $context)
    {
        $client = new \GuzzleHttp\Client();
        $apiKey = env('GROQ_API_KEY'); // Ensure your API key is correctly set in the .env file
        $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

        $contextString = json_encode($context, JSON_PRETTY_PRINT);

        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'llama3-8b-8192',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "
                                - You are a helpful assistant answering database questions. 
                                - Do not mention internal variables or technical terms like 'group_id', 'group_details', 'timetable_details', 'group_subjects' & etc and dont use the word 'database', id, variables, mysql, coding language in your responses. 
                                - dont give unrelated responses to the user's question.   
                                - if user ask for example how to add timetable, you can refer to guideline_details.                             
                                - Humanize your text responses and reply like an actual human, and omit your search process in a conversational and human-friendly manner.
                                - PLEASE Use memory to track previous queries like group and date to provide specific responses (VERY IMPORTANT).
                                - When a user asks a question, reply in simple, natural human language. For example: Instead of saying 'timetable_details', say 'the schedule'.
                                - please give date response in format dd/mm/yyyy like 24/02/2025.
                                - Users with their roles (1=admin, 2=lecturer, 3=student)
                                - Dont use variable in responses like groupsubject_details, group_details, timetable_details, subject_teacher_details, subject_student_details, upcoming_information, guideline_details.                             
                                " 
                                . json_encode($context),
                        ],
                        [
                            'role' => 'user',
                            'content' => $question,
                        ],
                    ],
                    'max_tokens' => 300,
                    'temperature' => 0.7,
                ],
            ]);

            $body = json_decode($response->getBody(), true);
            return $body['choices'][0]['message']['content'] ?? 'No response from the assistant.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // private function loadGuidelineContent()
    // {
    //     $filePath = resource_path('docs/classmate-guideline.md');

    //     try {
    //         if (file_exists($filePath)) {
    //             return file_get_contents($filePath);
    //         } else {
    //             return "Guideline file not found.";
    //         }
    //     } catch (\Exception $e) {
    //         return "Error loading guideline: " . $e->getMessage();
    //     }
    // }

    private function generateIntelligentContext()
{
    $users = DB::table('users')->get();
    $subjects = DB::table('subjects')->get();
    $classList = DB::table('class_list')->get();
    $informationDetails = DB::table('information_details')->get();
    $timetableDetails = DB::table('timetables')->get();
    $groupsubjectDetails = DB::table('group_subject')->get();
    $groupDetails = DB::table('groups')->get();

    // $guidelineContent = $this->loadGuidelineContent();

    $subjectTeachers = [];
    $subjectStudents = [];

    $groupSubjects = [];

    foreach ($groupDetails as $group) {
        // Filter subjects for the current group
        $groupSubjectsIds = $groupsubjectDetails->where('group_id', $group->id)->pluck('subject_id');
        $groupSubjectsDetails = $subjects->whereIn('id', $groupSubjectsIds);
        $groupSubjectsArray = $groupSubjectsDetails->pluck('name')->toArray();

        // Add the group and its subjects to the groupSubjects array
        $groupSubjects[$group->name] = $groupSubjectsArray;
    }

    foreach ($classList as $entry) {
        $user = $users->firstWhere('id', $entry->user_id);
        $subject = $subjects->firstWhere('id', $entry->subject_id);

        if (!$user || !$subject) continue;

        if ($user->role == 2) { // Lecturer
            $subjectTeachers[$subject->name] = [
                'name' => $user->name,
                'email' => $user->email,
            ];
        } elseif ($user->role == 3) { // Student
            if (!isset($subjectStudents[$subject->name])) {
                $subjectStudents[$subject->name] = [];
            }
            $subjectStudents[$subject->name][] = [
                'name' => $user->name,
                'email' => $user->email,
            ];
        }
    }
    


    // Summarized context with only Group 3 subjects
    $contextSummary = [
        'group_subjects' => $groupSubjects,
        'users' => $users,
        'subjects' => $subjects,
        'subject_teacher_details' => $subjectTeachers,
        'subject_student_details' => $subjectStudents,
        'upcoming_information' => $informationDetails,
        'timetable_details' => $timetableDetails,
        'groupsubject_details' => $groupsubjectDetails,
        'group_details' => $groupDetails,
        // 'guideline_details' => $guidelineContent,
    ];

    return $contextSummary;
}

    

    public function prompt(Request $request)
    {
        $question = $request->input('question');

        if (!$question) {
            return response()->json(['error' => 'Question is required'], 400);
        }

        $context = $this->generateIntelligentContext();
        $groqResponse = $this->fetchGroqResponse($question, $context);

        return response()->json(['response' => $groqResponse]);
    }

    
}
