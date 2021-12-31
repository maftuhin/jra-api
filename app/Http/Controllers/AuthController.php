<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string',
            'password' => 'required|string',
        ], [
            'phone.required' => 'Isi Nomer Telephone Dengan Benar'
        ]);

        $credential = $request->only(['phone', 'password']);

        if (!$token = Auth::attempt($credential)) {
            return response()->json(['message' => "Handphone atau Password tidak sesuai"], 401);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
            "birth_place" => "required",
            "birth_date" => "required",
            "gender" => "required",
            "phone" => "required",
            "member_card" => "required",
            "skill" => "required",
            "email" => "required",
            "license" => "required",
            "training_place" => "required",
            "training_date" => "required",
            "job" => "required",
        ]);

        return $validated;

        // try {
        //     $user = new User;
        //     $user->name = $request->input('name');
        //     $user->phone = $request->input('phone');
        //     $user->address = $request->input('address');
        //     $plainPassword = $request->input('password');
        //     $user->password = app('hash')->make($plainPassword);

        //     $user->save();

        //     return response()->json(['user' => $user, 'message' => 'USER CREATED'], 201);
        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'User Registration Failed!' . $e], 409);
        // }
    }
}
