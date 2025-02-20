<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $posts = Post::paginate(5);
        // return  Post::all();
        $posts = Post::with('comments', 'likes','profile')->orderBy('created_at', 'desc')->paginate(5);
        return response()->json([
            'posts' => $posts->items(),
            'nextPage' => $posts->nextPageUrl() // الرابط ديال الصفحة الجاية
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
        //
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
