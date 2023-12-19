<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            "users" => $users,
            "error" => null,
            "success" => true
        ]);
    }

    public function getApprovedEventsForUser($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "error" => "User not found!",
                "message" => "User not found!",
                "success" => false
            ], 404);
        }

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $events = Event::withCount([
            'guests as current_attendees' => function ($query) use ($id) {
                $query->where('user_id', $id)->where('approved', true);
            },
            'guests as total_attendees'
        ])
            ->whereHas('guests', function ($query) use ($id) {
                $query->where('user_id', $id)->where('approved', true);
            });

        // Optional date filtering
        if ($start_date) {
            $events->where('date', '>=', $start_date);
        }

        if ($end_date) {
            $events->where('date', '<=', $end_date);
        }

        $events = $events->get();

        return response()->json([
            "events" => $events,
            "error" => null,
            "success" => true
        ]);
    }

    public function findUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "error" => "User not found!",
                "message" => "User not found!",
                "success" => false
            ], 404);
        }

        return response()->json([
            "user" => $user,
            "error" => null,
            "success" => true
        ]);
    }

    public function getUserByEmailAndPassword(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                "error" => "User not found!",
                "message" => "User not found!",
                "success" => false
            ], 404);
        }

        // Verify the password
        if (Hash::check($password, $user->password)) {
            // Password is correct, return user information
            return response()->json([
                "user" => $user,
                "error" => null,
                "success" => true
            ]);
        } else {
            // Password is incorrect
            return response()->json([
                "error" => "Incorrect password!",
                "message" => "Incorrect password!",
                "success" => false
            ], 401);
        }
    }

    public function signupUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
                "error" => true,
                "success" => false
            ], 422); // 422 Unprocessable Entity
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        // login
        $token = $user->createToken("auth_token")->plainTextToken;

        // redirect
        return response()->json([
            "user" => $user,
            "token" => $token,
            "error" => null,
            "success" => true
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
                "error" => true,
                "success" => false
            ], 422); // 422 Unprocessable Entity
        }

        $user = User::where("email", $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken("auth_token")->plainTextToken;
            return response()->json([
                "user" => $user,
                "token" => $token,
                "error" => null,
                "success" => true
            ]);
        } else {
            return response()->json([
                "message" => "User not found or incorrect password",
                "error" => true,
                "success" => false
            ], 401); // 401 Unauthorized
        }
    }

    public function adminLogin(Request $request) {
        $formFields = $request->validate([
            'email'=> ['required', 'email'],
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors([
            'email' => 'Invalid Credentials'
        ])->onlyInput();
    }

    public function showAdminSignup() {
        return view('signup');
    }

    public function adminSignup(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        // login
        auth()->login($user);

        // redirect
        return redirect('/')->with('message', "User created and logged in!");
    }

    public function adminLogout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/signup')->with('message', 'You have been logged out!');
    }
}
