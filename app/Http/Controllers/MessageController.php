<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\notification;
use App\Models\ThongBaoChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Send a message to a group
    public function store(Request $request, MessageGroup $group)
    {
        $this->authorize('view', $group);

        $request->validate([
            'content' => 'required_without:attachments|string|nullable',
            'attachments.*' => 'nullable|file|image|max:10240', // 10MB max per file
        ]);

        $messageData = [
            'user_id' => Auth::id(),
            'content' => $request->content,
        ];

        // Xử lý nhiều file đính kèm
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = $path;
            }
            // Lưu dưới dạng JSON nếu cần lưu nhiều file
            $messageData['attachment'] = json_encode($attachments);
        }

        $message = $group->messages()->create($messageData);

        // Đánh dấu đã đọc và gửi thông báo (giữ nguyên)
        $message->reads()->create([
            'user_id' => Auth::id(),
            'read_at' => now(),
        ]);

        foreach ($group->users->where('id', '!=', Auth::id()) as $receiver) {
            ThongBaoChat::create([
                'user_id' => $receiver->id,
                'title' => 'Tin nhắn mới từ ' . Auth::user()->name,
                'message' => $group->is_group
                    ? 'Nhóm "' . $group->name . '" có tin nhắn mới'
                    : 'Bạn có tin nhắn mới từ ' . Auth::user()->name,
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Tin nhắn đã được gửi');
    }

    // Delete a message
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return back()->with('success', 'Message deleted');
    }
}