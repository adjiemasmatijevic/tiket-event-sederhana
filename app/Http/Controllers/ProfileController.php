<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('Profile', compact('user'));
    }

    public function update_photo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $directory = public_path('storage/profile_pictures');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        try {
            $manager = new ImageManager(new Driver());
            $attachment = $request->file('photo');
            $filename   = md5(time());
            $path       = public_path('storage/profile_pictures');
            $fullPath   = $path . '/' . $filename . '.webp';

            $img = $manager->read($attachment->getRealPath());
            $img->toWebp(50)->save($fullPath);

            if ($user->profile_picture != 'default') {
                $oldPhotoPath = public_path('storage/profile_pictures/' . $user->profile_picture . '.webp');
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $user->profile_picture = $filename;
            $user->save();
        } catch (\Exception $e) {
            Log::error('Profile Controller: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update profile photo');
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully');
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64|unique:users,name,' . Auth::user()->id,
            'gender' => 'required|string|in:male,female',
            'phone' => 'required|string|max:20|unique:users,phone,' . Auth::user()->id,
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        try {
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->save();
        } catch (\Exception $e) {
            Log::error('Profile Controller: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update profile');
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CurrentPassword' => 'required|string',
            'NewPassword' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'ConfirmPassword' => 'required|same:NewPassword',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!password_verify($request->CurrentPassword, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect')->withInput();
        }

        try {
            $user->password = bcrypt($request->NewPassword);
            $user->save();
        } catch (\Exception $e) {
            Log::error('Profile Controller: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to change password');
        }

        return redirect()->back()->with('success', 'Password changed successfully');
    }
}
