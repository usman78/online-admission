<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationCodeMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    //
    public function registerLoad(){
       
    return view('register');
    }
    public function register(Request $request): JsonResponse
{
    try {
        // Validate the form data
        $validatedData = $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('online_admission_users', 'email')
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validatedData['user_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']), // Hash the password
        ]);
        // Generate and set confirmation code and expiration
        $user->generateConfirmationCode();
        $user->save(); // Save the user with confirmation code
        // Send confirmation email
        Mail::to($user->email)->send(new ConfirmationCodeMail($user->confirmation_code));

        // Return a success response
        return response()->json(['success' => true,'message' => __('Registration successful. Please check your email for a confirmation code.')], 200);


    } catch (ValidationException $e) {
        // Return validation errors
        return response()->json(['errors' => $e->errors()], 422);

    } catch (\Exception $e) {
        // Log the exception for debugging purposes
        Log::error('Registration error: ' . $e->getMessage());

        // Return a general error response
        return response()->json(['error' => __('Something went wrong. Please try again.')], 500);
    }
}
public function showConfirmationForm(Request $request)
{
    $email = $request->query('email');
    $user = User::where('email', $email)->first();

    if (!$user || !$user->confirmation_code) {
        return view('confirm_email', [
            'error' => 'Invalid or expired confirmation request.',
            'email' => $email
        ]);
    }

    $expiresAt = $user->confirmation_code_expires_at;

    return view('confirm_email', [
        'email' => $email,
        'expiresAt' => $expiresAt,
    ]);
}
public function confirmEmail(Request $request): JsonResponse
{
    // Validate the request data
    $validated = $request->validate([
        'email' => 'required|email',
        'confirmation_code' => 'required|string|size:6',
    ]);

    // Begin a database transaction
    DB::beginTransaction();

    try {
        // Retrieve the user based on email, confirmation code, and code expiration
        $user = User::where('email', $request->email)
                    ->where('confirmation_code', $request->confirmation_code)
                    ->where('confirmation_code_expires_at', '>', now())
                    ->first();

        if ($user) {
            // Attempt to update the user record
            $updateSuccessful = $user->update([
                'confirmation_code' => null,
                'confirmation_code_expires_at' => null,
                'is_email_verify' => 1
            ]);

            // Check if the update was successful
            if ($updateSuccessful) {
                // Commit the transaction
                DB::commit();
                return response()->json([
                    'success' => __('Your email has been confirmed!'),
                    'redirect' => '/' // Redirect to home page
                ], 200);
            } else {
                // Rollback the transaction if the update fails
                DB::rollBack();
                return response()->json(['error' => __('Failed to update user record.')], 500);
            }
        } else {
            // Rollback the transaction if user is not found or confirmation code is invalid
            DB::rollBack();
            return response()->json(['error' => __('Invalid or expired confirmation code.')], 400);
        }
    } catch (\Exception $e) {
        // Rollback the transaction if an exception occurs
        DB::rollBack();
        // Log the exception message for debugging
        Log::error('Update failed: ' . $e->getMessage());
        return response()->json(['error' => __('An error occurred while updating the user record.')], 500);
    }
}
public function loginLoad(){
       
    return view('login');
    }
public function login(Request $request): JsonResponse
{
    // Validate login credentials
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Attempt to log the user in
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check if email is verified
        if (!$user->is_email_verify) {
            // Regenerate confirmation code
            $user->regenerateConfirmationCode();
            
            // Send confirmation email
            Mail::to($user->email)->send(new ConfirmationCodeMail($user->confirmation_code));

            // Log out the user
            Auth::logout();
            
            // Return response to indicate that email verification is required
            return response()->json([
                'error' => 'Please verify your email address.',
                'redirect' => route('confirmation.form', ['email' => $user->email])
            ], 403);
        }

        // Return success response if login is successful
        return response()->json(['success' => 'Login successful.'], 200);
    } else {
        // Return error response if login fails
        return response()->json(['error' => 'Invalid email or password.'], 401);
    }
}   
    public function showforgetPasswordForm()
    {
        return view('forgetPasswordForm');
    }
    public function sendResetToken(Request $request)
    {
        // Validate the email field
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);
    
        $email = $validatedData['email'];
    
        // Check if the email exists in the users table
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return response()->json(['success' => false,'error' => 'The provided email address does not exist.'], 404);
        }
    
        // Generate a 6-digit token
        $token = Str::random(6); // Generate a random 6-digit number
    
        // Save the token and its expiration to the database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );
    
        // Send the password reset token via email
        try {
            Mail::to($email)->send(new \App\Mail\PasswordResetTokenMail($token));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email.'], 500);
        }
        
    
        return response()->json(['success' =>true , 'msg' => 'Password reset token sent to your email.'], 200);
    }
    
  public function showPasswordResetForm(){
    return  view('passwordReset');
  }
    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string:6',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ]);
    
        $email = $validatedData['email'];
        $token = $validatedData['token'];
      
        
        $passwordReset = DB::table('password_reset_tokens')
                            ->where('email', $email)
                            ->where('token', $token)
                            ->first();
    
        if (!$passwordReset) {
            return response()->json(['error' => 'Invalid or expired token.'], 400);
        }
    
        // Update the user's password
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($validatedData['password']);
        $user->save();
    
        // Delete the token from the database
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    
        return response()->json(['success' => 'Password has been reset successfully.'], 200);
    }
    
}
