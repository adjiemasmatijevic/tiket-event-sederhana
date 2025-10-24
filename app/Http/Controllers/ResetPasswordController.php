<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResetPassword;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class ResetPasswordController extends Controller
{
    public function RequestReset()
    {
        return view('ForgotPassword');
    }

    public function SendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        try {
            ResetPassword::create([
                'user_id' => $user->id,
                'expired_at' => now()->addMinutes(10),
            ]);

            $link = route('reset.password', ['id' => ResetPassword::latest()->first()->id]);

            Mail::to($user->email)->send(new ResetPasswordMail($link));
        } catch (Exception $e) {
            Log::error('Failed to send reset link: '  . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send reset link to your email');
        }

        return redirect()->route('login')->with('success', 'Reset link sent to your email');
    }

    public function ResetPassword($id)
    {
        $reset = ResetPassword::where('id', $id)->first();

        if (!$reset) {
            return redirect()->route('home');
        }

        if (now()->greaterThan($reset->expired_at)) {
            return redirect()->route('home');
        }

        return view('ResetPassword', compact('reset'));
    }

    public function UpdatePassword(Request $request)
    {
        $request->validate([
            'reset_id' => 'required|exists:reset_passwords,id',
            'NewPassword' => 'required|string|min:8',
            'ConfirmPassword' => 'required|same:NewPassword',
        ]);

        $reset = ResetPassword::where('id', $request->reset_id)->first();

        if (now()->greaterThan($reset->expired_at)) {
            return back()->withErrors(['reset_id' => 'Reset link has been expired'])->withInput();
        }

        $user = User::where('id', $reset->user_id)->first();

        try {
            $user->password = bcrypt($request->NewPassword);
            $user->save();
            $reset->delete();
        } catch (Exception $e) {
            Log::error('Failed to update password: '  . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update password');
        }

        return redirect()->route('login')->with('success', 'Password updated successfully');
    }
}
