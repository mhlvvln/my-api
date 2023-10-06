<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'teacher' => 'boolean',
        ], [
            'required' => 'Поле :attribute обязательно для заполнения.',
            'string' => 'Поле :attribute должно быть строкой.',
            'email' => 'Поле :attribute должно быть корректным адресом электронной почты.',
            'unique' => 'Пользователь с таким :attribute уже существует.',
            'min' => 'Минимальная длина поля :attribute должна быть :min символов.',
            'boolean' => 'Поле :attribute должно быть логическим значением.',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Есть неверные данные',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'teacher' => $request->teacher,
        ]);

        return response()->json(
            [
                'status' => true,
                'user' => $user
            ]
        );
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Неверные логин и пароль'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Ошибка при создании токена'], 500);
        }

        $user = auth()->user();

        return response()->json([
            'token' => $token,
            'id' => $user->id, 
            'photo' => $user->photo
        ]);
    }
}
