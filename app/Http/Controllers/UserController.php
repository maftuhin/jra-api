<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['search', 'detail']]);
    }

    function search(Request $request, User $user)
    {
        $user = $user->newQuery();
        $user->select("id", "name", "address");
        if ($request->has("province")) {
            $user->where('province', $request->input('province'));
        }
        if ($request->has("city")) {
            $user->where('city', $request->input('city'));
        }
        if ($request->has("name")) {
            $name = $request->input('name');
            $user->where('name', 'LIKE', '%' . $name . '%');
        }

        $result = $user->orderBy("name", "ASC")->limit(10)->get();
        if ($result->count() > 0) {
            return response()->json($result);
        } else {
            return response(["message" => "Peruqyah tidak ditemukan"], 500);
        }
    }

    function detail(Request $request)
    {
        $userId = $request->input("id");
        $user = User::where("id", $userId)->first();
        return response()->json($user);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    function update(Request $request)
    {
        $token = auth()->user();
        $this->validate($request, [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        $address = $request->input("address");
        $name = $request->input("name");
        $phone = $request->input("phone");
        $profession = $request->input("profession");
        $skill = $request->input("skill");

        if ($token != null) {
            $user = User::find($token->id);

            $user->address = $address;
            $user->phone = $phone;
            $user->name = $name;
            $user->skill = $skill;
            $user->profession = $profession;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                $storagePath = './images/profiles';
                $originalFileName = $photo->getClientOriginalName();
                $fileExtension = $photo->getClientOriginalExtension();
                $imageFileName = $user->id . '.' . $fileExtension;

                $fileOutPut = $photo->move($storagePath, $imageFileName);
                if (is_file($fileOutPut)) {
                    $user->image = url() . '/images/profiles/' . $imageFileName;
                }
            }

            $user->save();
            return response($user);
        } else {
            return response()->json([
                "message" => "Unauthorized"
            ], 401);
        }
    }

    function password(Request $request)
    {
        $token = auth()->user();
        $this->validate($request, [
            'old_password' => 'required|string',
            'new_password' => 'required|string',
        ]);
        $current = $request->input("old_password");
        $password = $request->input("new_password");

        if (Hash::check($current, $token->password)) {
            $user = User::find($token->id);
            $user->password = Hash::make($password);
            $user->save();

            return response(["message" => "Password Berhasil Diubah"]);
        } else {
            return response(["message" => "Password Lama Tidak Sesuai"], 500);
        }
    }
}
