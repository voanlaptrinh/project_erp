<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Send a message to a group
    public function store(Request $request, MessageGroup $group)
    {
        $this->authorize('view', $group);
        
        $request->validate([
            'content' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);
        
        $messageData = [
            'user_id' => Auth::id(),
            'content' => $request->content,
        ];
        
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $messageData['attachment'] = $path;
        }
        
        $message = $group->messages()->create($messageData);
        
        // Mark the message as read by the sender
        $message->reads()->create([
            'user_id' => Auth::id(),
            'read_at' => now(),
        ]);
        
        return back()->with('success', 'Message sent');
    }

    // Delete a message
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);
        
        $message->delete();
        
        return back()->with('success', 'Message deleted');
    }
}