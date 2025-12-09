<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $ticket_id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['ticket_id'] = $ticket_id;
        Comment::create($validated);

        return redirect()->route('tickets.show', $ticket_id)
            ->with('success', 'Comment added successfully!');
    }
}
