<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Exception;

class AuthController extends Controller
{
    public function login()
    {
        return view('Login');
    }

    public function login_handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            auth::login($user);

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Invalid email or password');
    }

    public function register()
    {
        return view('Register');
    }

    public function register_handle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64|unique:users',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20|unique:users',
            'address' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            Auth::login($user);
            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Log::error('Register Controller: ' . $e->getMessage());
            return back()
                ->with('error', 'Something went wrong')
                ->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
