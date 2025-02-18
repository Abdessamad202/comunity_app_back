# Community App - API Documentation

This is the API documentation for the **Community App**. The app provides authentication functionality, user registration, profile management, and login/logout features.

## Routes Overview

### 1. **Authenticated Routes** (Requires Sanctum Authentication)

These routes are protected and require the user to be authenticated via Sanctum.

-   **GET /user**

    -   Returns the currently authenticated user's data.
    -   **Middleware**: `auth:sanctum`

-   **GET /profile/{profile}**
    -   Displays the profile information for a given profile ID.
    -   **Controller**: `ProfileController@show`

### 2. **Guest Routes**

These routes are accessible to guests (unauthenticated users) and handle the registration process, login, and logout.

#### Register Routes

-   **POST /register/step-1**

    -   Starts the user registration process. This step will collect sensitive data.
    -   **Controller**: `Authentication@registerStep1`

-   **POST /register/resend-code/{user}**

    -   Resends the email verification code to the user for email verification.
    -   **Controller**: `Authentication@resendCode`

-   **POST /register/step-2/{user}**

    -   Collects the second step of registration (user verification).
    -   **Controller**: `Authentication@registerStep2`

-   **POST /register/step-3/{user}**
    -   Collects the final profile information for registration.
    -   **Controller**: `Authentication@registerStep3`

#### Login and Logout Routes

-

# comunity_app_back

<!--
use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create 200 users with profiles
        User::factory(200)->create()->each(function ($user) {
            // Create profile for each user
            Profile::factory()->create([
                'user_id' => $user->id, // Associate profile with user
            ]);

            // Create 4 posts for each user
            Post::factory(4)->create([
                'user_id' => $user->id, // Associate posts with the user
            ])->each(function ($post) use ($user) {
                // Create likes for each post (random users liking the post)
                Like::factory(rand(1, 5))->create([
                    'user_id' => User::inRandomOrder()->first()->id, // Random users liking the post
                    'post_id' => $post->id,
                ]);

                // Create comments for each post (random users commenting)
                Comment::factory(rand(1, 5))->create([
                    'user_id' => User::inRandomOrder()->first()->id, // Random users commenting
                    'post_id' => $post->id,
                ]);
            });
        });
    }
}

 -->
