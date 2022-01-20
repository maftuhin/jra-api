<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            "phone" => "required|string",
            "password" => "required|string",
        ], [
            "phone.required" => "Isi Nomer Telephone Dengan Benar",
            "password.required" => "Isi Password Terlebih Dahulu",
        ]);

        $credential = $request->only(['phone', 'password']);

        if (!$token = Auth::attempt($credential)) {
            return response()->json(['message' => "Handphone atau Password tidak sesuai"], 401);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $me = auth()->user();
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
            "birth_place" => "required",
            "birth_date" => "required",
            "gender" => "required",
            "phone" => "required|unique:users,phone",
            "karta" => "required",
            "skill" => "required",
            "email" => "required",
            "license" => "required",
            "training_place" => "required",
            "training_date" => "required",
            "job" => "required",
        ], [
            "name.required" => "Isi Nama Praktisi",
            "address.required" => "Isi Alamat Praktisi Dengan Benar",
            "phone.unique" => "No Handphone sudah digunakan",
        ]);

        $data = [
            "name" => $validated["name"],
            "address" => $validated["address"],
            "birth_place" => $validated["birth_place"],
            "birth_date" => $validated["birth_date"],
            "gender" => $validated["gender"],
            "phone" => $validated["phone"],
            "karta" => $validated["karta"],
            "skill" => $validated["skill"],
            "email" => $validated["email"],
            "piagam" => $validated["license"],
            "training_place" => $validated["training_place"],
            "training_date" => $validated["training_date"],
            "profession" => $validated["job"],
            "province" => $me->province,
        ];
        if ($me->role == "PC") {
            $data["city"] = $me->city;
        }
        $insert = User::insert($data);
        return $this->actionResult($insert, "input_praktisi");
    }
}
