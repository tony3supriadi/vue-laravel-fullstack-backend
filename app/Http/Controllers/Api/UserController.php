<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name');

        if (request()->get('src')) {
            $users->where('name', 'like', '%' . request()->get('src') . '%');
            $users->orWhere('email', 'like', '%' . request()->get('src') . '%');
        }

        if (request()->get('role')) {
            $users->where('role', request()->get('role'));
        }

        $results = $users->paginate(10);
        return $this->sendResponse($results, 'Users retrieved successfully.');
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendError('User not found.');
        }
        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/',
            'password_confirmation' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create($request->all());
        return $this->sendResponse($user, 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'password_confirmation' => 'nullable',
            'role' => 'nullable'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::find($id);
        if (!$user) {
            return $this->sendError('User not found.');
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if ($request->role) {
            $user->role = $request->role;
        }

        $user->save();

        return $this->sendResponse($user, 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->sendError('User not found.');
        }

        $user->delete();

        return $this->sendResponse(null, 'User deleted successfully.');
    }
}
