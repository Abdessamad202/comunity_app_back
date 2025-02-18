<?php

namespace App\Http\Controllers\Api\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Guest\LoginRequest;
use App\Http\Requests\Guest\CheckCodeRequest;
use App\Http\Requests\Guest\RegisterRequest;
use App\Http\Requests\Guest\ProfileRegistrationRequest;
use App\Http\Requests\MailRequest;
use App\Jobs\ForgetPasswordMail;
use App\Jobs\SendVerificationCode;
use App\Models\ForgetPasswordProcess;
use App\Models\Profile;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Authentication extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['registerStep1', 'login', 'verifyMail']),
        ];
    }
    // Step 1: Register the user
    public function registerStep1(RegisterRequest $request)
    {
        $data = $request->validated(); // Validate and get data from request
        $code = $this->generateCode(); // Generate a unique verification code

        // Create a new user in the database
        $user = User::create($data);
        $user->refresh(); // Refresh the user object to get the new ID
        // Store the verification code in the database
        $this->storeCode($user->id, $code, $user->email, SendVerificationCode::class, Verification::class);
        // Return a response with instructions
        return response()->json([
            'user' => [
                'id' => $user->id,
                'step' => $user->step,
                'token' => $user->createToken('authToken')->plainTextToken

            ],
            'message' => 'Verification code sent successfully',
        ]);
    }
    // resend the code to validate the email
    public function resendVerificationEmailCode(User $user)
    {
        // $phase = $user->load('forgetPasswordProcess')->phase;
        if ($user->id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot resend the verification code at this step'], 400);
        }
        // if ($phase !== 1) {
        //     return response()->json(['success' => false, 'message' => 'You cannot resend the verification code at this step'], 400);
        // }

        $code = $this->generateCode();
        $this->storeCode($user->id, $code, $user->email, ForgetPasswordMail::class, ForgetPasswordProcess::class);
        return response()->json(['success' => true, 'message' => 'Verification code resent successfully'], 200);
    }
    public function resendConfirmationEmailCode(User $user)
    {
        if ($user->id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot resend the verification code at this step'], 400);
        }
        if ($user->step !== 1) {
            return response()->json(['success' => false, 'message' => 'You cannot resend the verification code at this step'], 400);
        }

        $code = $this->generateCode();
        $this->storeCode($user->id, $code, $user->email, SendVerificationCode::class, Verification::class);
        return response()->json(['success' => true, 'message' => 'Verification code resent successfully'], 200);
    }

    // Step 2: Verify the user's email using the code
    public function registerStep2(CheckCodeRequest $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot verify the email at this step'], 400);
        }
        $user->load('verification'); // Load associated verification data
        // Check if the provided verification code matches the stored hashed code
        if (Hash::check($request->code, $user->verification->hashed_code)) {
            // Update the user's step to 2 (email verified)
            $user->update(['step' => 2]);

            // Delete the verification record since it's no longer needed
            $user->verification->delete();

            // Return the token as a response
            return response()->json(['success' => true, 'message' => 'Email verified successfully', 'user' => ['step' => $user->step]], 200);
        }

        // Return an error response if the code is invalid
        return response()->json([
            'success' => false,
            'errors' => [

                "code" => [
                    "The code does not match."
                ]
            ]
        ], 400);
    }
    public function registerStep3(ProfileRegistrationRequest $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot create the profile at this step'], 400);
        }

        Profile::create(array_merge($request->validated(), ['user_id' => $user->id]));

        $user->update(['step' => 3]);

        return response()->json([
            'success' => true,
            'message' => 'Profile Data created successfully',
            'profileId' => $user->profile->id
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            // Authentication passed, get the authenticated user with their profile
            $user = Auth::user()->load('profile');

            // Check if the user has completed registration (step 3)
            $token = $user->createToken('authToken')->plainTextToken;
            if ($user->step == 3) {

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'token' => $token,
                        'profileId' => $user->profile?->id, // Prevents error if profile is null
                    ]
                ], 200);
            }

            // User has not completed registration
            return response()->json([
                'success' => false,
                'message' => 'You need to complete the registration first',
                'user' => [
                    'id' => $user->id,
                    'token' => $token,
                    'step' => $user->step
                ]
            ]);
        }
        // Authentication failed (invalid credentials)
        return response()->json([
            'success' => false,
            'errors' => [
                "email" => ["The provided credentials are incorrect."]
            ]
        ], 401);
    }
    public function verifyMail(MailRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $code = $this->generateCode();
        $this->storeCode($user->id, $code, $user->email, ForgetPasswordMail::class, ForgetPasswordProcess::class);
        return response()->json(['success' => true, 'message' => 'Verification code resent successfully', "user" => [
            'id' => $user->id,
            'token' => $user->createToken('authToken')->plainTextToken,
            'phase' => $user->forgetPasswordProcess->phase
        ]], 200);
    }
    public function checkCode(CheckCodeRequest $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot verify the email at this step'], 400);
        }
        $user->load('forgetPasswordProcess'); // Load associated verification data
        // Check if the provided verification code matches the stored hashed code
        if (Hash::check($request->code, $user->forgetPasswordProcess->hashed_code)) {
            // Update the user's step to 2 (email verified)
            $user->forgetPasswordProcess->update(['phase' => 2]);
            $user->refresh();
            return response()->json(['success' => true, 'message' => 'Email verified successfully', 'user' => ['phase' => $user->forgetPasswordProcess->phase]], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code',
            'errors' => [
                "code" => [
                    "The code does not match."
                ]
            ]
        ], 401);
    }
    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        if ($user->id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot change the password at this step', 'user' => ['profileId' => $user->profile->id]], 400);
        }
        $user->load('forgetPasswordProcess'); // Load associated verification data
        $user->forgetPasswordProcess->delete();
        $user->update(['password' => Hash::make($request->password)]);
        return response()->json(['success' => true, 'message' => 'Password changed successfully', 'user' => ['profileId' => $user->profile->id]], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();  // Log the user out using Sanctum
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    // Store the verification code in the database
    protected function storeCode(int $user_id, int $code, string $email, string $jobClass, string $modelClass)
    {
        $modelClass::updateOrCreate(
            ['user_id' => $user_id],
            ['hashed_code' => Hash::make($code)]
        );

        // Dispatch the given job dynamically
        $jobClass::dispatch($email, $code);
    }


    // Generate a random verification code
    protected function generateCode()
    {
        $length = config('auth.verification_code_length', 6);
        return random_int(pow(10, $length - 1), pow(10, $length) - 1);
    }
}
