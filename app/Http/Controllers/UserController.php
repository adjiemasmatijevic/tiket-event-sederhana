<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function users()
    {
        return view('admins.Users');
    }

    public function users_data()
    {
        $query = User::select(['id', 'name', 'email', 'role', 'created_at'])
            ->where('role', '!=', 'admin')
            ->orderByRaw("FIELD(role, 'checker', 'user')")
            ->orderBy('name', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($user) {
                return '<button type="button" class="btn btn-warning btn-rounded"
                            style="color: #ffffff;"
                            data-toggle="modal"
                            data-target="#EditRoleModal"
                            data-id="' . $user->id . '"
                            data-name="' . htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') . '"
                            data-email="' . htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') . '"
                            data-role="' . $user->role . '">
                            <i class="fa fa-edit"></i> Edit Role
                        </button>';
            })->make(true);
    }

    public function users_update_role(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', '!=', 'admin');
                }),
            ],
            'role' => [
                'required',
                Rule::in(['checker', 'user']),
            ],
        ]);

        $userExists = User::where('id', $request->id)->first();
        if ($userExists && $userExists->role === 'admin') {
            return back()->with('error', 'Admin role cannot be changed.');
        }


        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $user = User::findOrFail($request->id);

            if ($user->role === 'admin') {
                return back()->with('error', 'Admin role cannot be changed.');
            }

            $user->role = $request->role;
            $user->save();
        } catch (Exception $e) {
            Log::error('Gagal memperbarui role user: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui role user');
        }

        return back()->with('success', 'Role user berhasil diperbarui');
    }
}
