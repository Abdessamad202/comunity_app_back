<?php
namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum')];
    }

    /**
     * Like a post.
     */
    public function likePost(Post $post)
    {
        Like::firstOrCreate([
            'user_id' => Auth::user()->id, // Ensure correct column
            'post_id' => $post->id,
        ]);

        return response()->json(['message' => 'Post liked!']);
    }

    /**
     * Unlike a post.
     */
    public function unLikePost(Post $post)
    {
        $post->likes()->where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Like removed.']);
    }
}
