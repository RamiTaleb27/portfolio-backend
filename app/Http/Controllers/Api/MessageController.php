<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller {
    
    // Public: anyone can send a message
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create($validated);
        return response()->json($message, 201);
    }

    // Protected: only admin can view
    public function index() {
        return Message::latest()->get();
    }

    public function show(Message $message) {
        return $message;
    }

    public function update(Request $request, Message $message) {
        $validated = $request->validate([
            'read' => 'boolean',
        ]);

        $message->update($validated);
        return response()->json($message);
    }

    public function destroy(Message $message) {
        $message->delete();
        return response()->noContent();
    }
}