<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    //
    public function index()
    {
        return view('chatbot.chat');
    }

    public function fetchGroqResponse($question, $context) {
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
                            'content' => "You are a helpful assistant answering database questions. 
                                Reply like an actual human, and omit your search process.
                                The context provided includes:
                                - Users with their roles (1=admin, 2=lecturer, 3=student)
                                - Subjects and their details
                                - Class lists showing which users (students/lecturers) are assigned to subjects
                                - Information details with scheduled dates for assignments/exams/classes
                                - Information detail is linked to subjects by the subject_id field on information detail

                                The subject_teachers and subject_students arrays show the direct relationships.
                                Use these to accurately identify lecturers and students for each subject." 
                                . json_encode($context),
                        ],
                        [
                            'role' => 'user',
                            'content' => $question,
                        ],
                    ],
                    'max_tokens' => 300,
                    'temperature' => 0.7
                ],
            ]);
    
            $body = json_decode($response->getBody(), true);
            return $body['choices'][0]['message']['content'] ?? 'No response from the assistant.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

private function generateIntelligentContext()
{
    $users = DB::table('users')
    ->select('id', 'name', 'email', 'role')
    ->get();

    $subjects = DB::table('subjects')
        ->select('id', 'name', 'code')
        ->get();
        
    $classList = DB::table('class_list')
        ->select('user_id', 'subject_id')
        ->get();
        
    $informationDetails = DB::table('information_details')
        ->select('id', 'subject_id', 'type', 'scheduled_at')
        ->get();

    $subjectTeachers = [];
    $subjectStudents = [];

    foreach ($classList as $entry) {
        $user = $users->firstWhere('id', $entry->user_id);
        $subject = $subjects->firstWhere('id', $entry->subject_id);
        
        if (!$user || !$subject) continue;

        if ($user->role == 2) { // Lecturer
            $subjectTeachers[$subject->name] = [
                'name' => $user->name,
                'email' => $user->email
            ];
        } elseif ($user->role == 3) { // Student
            if (!isset($subjectStudents[$subject->name])) {
                $subjectStudents[$subject->name] = [];
            }
            $subjectStudents[$subject->name][] = [
                'name' => $user->name,
                'email' => $user->email
            ];
        }
    }

    return [
        'users' => $users,
        'subjects' => $subjects,
        'subject_teachers' => $subjectTeachers,
        'subject_students' => $subjectStudents,
        'information_details' => $informationDetails,
        'role_mappings' => [
            1 => 'admin',
            2 => 'lecturer',
            3 => 'student'
        ]
    ];
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


