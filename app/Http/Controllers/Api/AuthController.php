<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Variable
     */
    private $_token_key = 'laravel-fullstack-api';

    /**
     * Login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $data['token'] = $user->createToken($this->_token_key)->plainTextToken;
            $data['name'] = $user->name;

            return $this->sendResponse($data, 'Login successfully.');
        }

        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
    }

    /**
     * Register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'password_confirmation' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 400);
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $data['token'] = $user->createToken($this->_token_key)->plainTextToken;
        $data['name'] = $user->name;

        return $this->sendResponse($data, 'Register successfully.');
    }

    /**
     * Logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->sendResponse(null, 'Logout successfully.');
    }
}
