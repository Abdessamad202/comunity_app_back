<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // protected function updatePicture(Post $post,int $id): void
    // {
    //     $post->update([
    //         'picture' => 'http://127.0.0.1:8000/imgs/pic'.$id.'.jpg'
    //     ]);
    // }
    public function run(): void
    {
        User::factory(250)->create();
        $users = User::all();
        // $users = User::whereBetween('id', [201, 204])->get();  //Retrieve all users

        foreach ($users as $user) {
            Profile::factory(1)->create([
                'user_id' => $user->id,
                'picture' => 'http://127.0.0.1:8000/imgs/profiles/pic' . ($user->id % 2) + 1 . '.jpg',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]);
        }
        // $profiles = Profile::all();
        foreach ($users as $user) {
            for($i = 1;$i<=5;$i++){
                Post::factory()->create([
                    'user_id' => $user->id,
                    'picture' => 'http://127.0.0.1:8000/imgs/posts/pic' . $i. '.jpg',
                ]);
            }
        }

        $posts = Post::all();
        foreach ($posts as $post) {
            $selectedUsers = $users->random(50); // Randomly select a user
            foreach ($selectedUsers as $user) {
                Like::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $user->id
                ]);
            }
            $selectedUsers2 = $users->random(30); // Randomly select a user
            foreach ($selectedUsers2 as $user) {
                Comment::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
