<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('admin.chat.index');
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $message],
            ],
        ]);
    
        // Debug full response
        logger($response->json());
    
        if (isset($response->json()['choices'][0]['message']['content'])) {
            return response()->json([
                'reply' => $response->json()['choices'][0]['message']['content'],
            ]);
        } else {
            return response()->json([
                'reply' => 'No response from AI.',
                'error' => $response->json(),
            ]);
        }
    }
    
}

