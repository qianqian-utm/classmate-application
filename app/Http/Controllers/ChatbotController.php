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
    
        try {
            $response = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/j    son',
                ],
                'json' => [
                    'model' => 'llama3-8b-8192', // Adjust the model if required
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are only answer questions about this system only. Ignore irrelevant queries like Whats the weather today?. The following is the system context: ' . json_encode($context),
                        ],
                        [
                            'role' => 'user',
                            'content' => $question,
                        ],
                    ],
                ],
            ]);
    
            $body = json_decode($response->getBody(), true);
            return $body['choices'][0]['message']['content'] ?? 'No response from the assistant.';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function prompt(Request $request)
{
    $question = $request->input('question');

    if (!$question) {
        return response()->json(['error' => 'Question is required'], 400);
    }

    
     $tables = DB::select('SHOW TABLES');

     $context = [];
 
     
     foreach ($tables as $table) {
         $tableName = $table->{'Tables_in_' . env('DB_DATABASE')}; 
         $tableData = DB::table($tableName)->get(); 
         $context[$tableName] = $tableData;
     }

    // $context = [
    //     'user_count' => $users->count(),
    //     'latest_user' => $users->last(),
    //     'user_emails' => $users->pluck('email')->toArray(),
    // ];

    // Fetch response from Groq API
    $groqResponse = $this->fetchGroqResponse($question, $context);

    return response()->json(['response' => $groqResponse]);
}


}


