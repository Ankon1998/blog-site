<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a newly created comment resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        // 1. Validation
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // 2. Create the comment
        $comment = new Comment();
        $comment->content = $validated['content'];
        $comment->user_id = auth()->id(); // Get the ID of the currently logged-in user

        // 3. Associate and save
        $post->comments()->save($comment);

        return back()->with('success', 'Your comment has been posted!');
    }

    /**
     * Remove the specified comment from storage. (New Method)
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        
        return back()->with('success', 'Comment successfully deleted.');
    }
}