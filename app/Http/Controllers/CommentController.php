<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum')];
    }
    /**
     * Display a listing of the resource.
     */

    public function showComments(Post $post)
    {
        $comments = $post->comments()->with('user.profile')->paginate(10);

        return response()->json([
            'comments' => $comments->items(), // التعليقات ديال الصفحة الحالية
            'nextPage' => $comments->nextPageUrl(), // الرابط ديال الصفحة الجاية
            'total' => $post->comments()->count(), // عدد التعليقات الكلي
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, Post $post)
    {
        // $comment = Comment::create($request->validated());
        Comment::create(
            array_merge($request->validated(), ['user_id' => Auth::id(), 'post_id' => $post->id])

        );
        return response()->json(['message' => 'Comment created!']);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        //
        $comment->update($request->validated());
        return response()->json(['message' => 'Comment updated!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted!']);
    }
}
