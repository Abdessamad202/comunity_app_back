<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(){
        return [new Middleware('auth:sanctum')];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id(); // جلب ID ديال المستخدم الحالي
        $posts = Post::with('user.profile','comments.user.profile')
            ->withCount([ 'likes'])
            ->with(['comments'=> function ($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->withExists(['likes as liked' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]) //  liked: true/false
            ->orderBy('created_at', 'asc')
            ->paginate(5);
        return response()->json([
            'posts' => $posts->items(),
            'nextPage' => $posts->nextPageUrl(),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {

        return $post->load('user.profile', 'comments.user.profile');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
