<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
     public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'message' => 'required|max:5000',
        ]);

       

        $feedback = Feedback::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Feedback enviado com sucesso.',
            'feedback' => $feedback
        ], 201);
    }

    public function index()
    {
        return Feedback::with('user')
            ->latest()
            ->get();
    }

}
