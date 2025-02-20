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
    protected function updatePicture(int $id,string $str): string
    {
        if ($str == 'posts') {
            return 'http://127.0.0.1:8000/imgs/posts/pic' . $id % 5 + 1 . '.jpg';
            # code...
        }
        return 'http://127.0.0.1:8000/imgs/' . $str . '/pic' . $id % 2 + 1 . '.jpg';
    }
    public function run(): void
    {
        User::factory(200)->create();
        $users = User::all();  //Retrieve all users
        foreach ($users as $user) {
            Profile::factory(1)->create([
                'user_id' => $user->id,
                'picture' => 'http://127.0.0.1:8000/imgs/profiles/pic' . ($user->id % 2) + 1 . '.jpg',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]);
        }
        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            Post::factory(4)->create([
                'profile_id' => $profile->id,
                'picture' => 'http://127.0.0.1:8000/imgs/posts/pic' . (($profile->id % 4) + 1) . '.jpg',
            ]);
        }

        $posts = Post::all();
        foreach ($posts as $post) {
            $selectedProfiles = $profiles->random(50); // Randomly select a user
            foreach ($selectedProfiles as $profile) {
                Like::factory()->create([
                    'post_id' => $post->id,
                    'profile_id' => $profile->id
                ]);
            }
            $selectedProfiles2 = $profiles->random(30); // Randomly select a user
            foreach ($selectedProfiles2 as $profile) {
                Comment::factory()->create([
                    'post_id' => $post->id,
                    'profile_id' => $profile->id
                ]);
            }
        }

        }
}
