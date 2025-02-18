<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ProfileController extends Controller
{
    public function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Profile $profile)
    {
        try {
            // Load the user's profile with specific fields for the associated user
            $profileData = $profile->load(['user' => function ($query) {
                $query->select('id', 'email'); // Specify only required fields
            }]);

            // Return a success response with the profile data
            return response()->json([
                'profile' => $profileData
            ], 200);
        } catch (\Exception $exception) {
            // Handle potential errors and return a proper error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching user data.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
