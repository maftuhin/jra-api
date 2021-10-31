<?php

namespace App\Http\Controllers;

use App\Models\SkillUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['search', 'detail', 'praktisi']]);
    }

    function search(Request $request, User $user)
    {
        $user = $user->newQuery();
        $user->select("id", "name", "address","gender");
        if ($request->has("province")) {
            $user->where('province', $request->input('province'));
        }
        if ($request->has("city")) {
            $user->where('city', $request->input('city'));
        }
        if ($request->has("name")) {
            $name = $request->input('name');
            $user->where('name', 'LIKE', '%' . $name . '%');

            if ($name != "") {
                $user->orderBy("name", "ASC");
            } else {
                $user->inRandomOrder();
            }
        }

        $result = $user->limit(100)->get();
        if ($result->count() > 0) {
            return response()->json($result);
        } else {
            return response(["message" => "Peruqyah tidak ditemukan"], 500);
        }
    }

    function praktisi(Request $request, User $user)
    {
        $user = $user->newQuery();
        $user->select("id", "name", "address","gender");
        if ($request->has("province")) {
            $user->where('province', $request->input('province'));
        }
        if ($request->has("city")) {
            $user->where('city', $request->input('city'));
        }
        if ($request->has("district")) {
            $user->where("districts", $request->input("district"));
        }

        $result = $user->inRandomOrder()->get();
        if ($result->count() > 0) {
            return response()->json($result);
        } else {
            return response(["message" => "Peruqyah tidak ditemukan"], 500);
        }
    }

    function detail(Request $request)
    {
        $userId = $request->input("id");
        $skill = SkillUser::select("skills.skill")
            ->join("skills", "skills.id", "=", "skill_users.skill")
            ->where("user", $userId)
            ->where("status", 1)
            ->get();

        $user = User::where("id", $userId)->first();
        $user["skills"] = $skill;
        return response()->json($user);
    }

    public function me()
    {
        $user = auth()->user();
        $skill = SkillUser::select("skills.skill")
            ->join("skills", "skills.id", "=", "skill_users.skill")
            ->where("user", $user->id)
            ->where("status", 1)
            ->get();
        $user["skills"] = $skill;
        return response()->json($user);
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
        $gender = $request->input("gender");
        $phone = $request->input("phone");
        $license = $request->input("license");
        $profession = $request->input("profession");
        $skill = $request->input("skill");
        $birth_place = $request->input("birth_place");
        $birth_date = $request->input("birth_date");
        $training_place = $request->input("training_place");
        $training_date = $request->input("training_date");
        $phone_visibility = $request->input("phone_visibility");

        if ($token != null) {
            $user = User::find($token->id);

            $user->address = $address;
            $user->phone = $phone;
            $user->piagam = $license;
            $user->name = $name;
            $user->gender = $gender;
            $user->skill = $skill;
            $user->profession = $profession;
            $user->birth_place = $birth_place;
            $user->training_place = $training_place;
            $user->phone_visibility = $phone_visibility;
            if ($birth_date != "") {
                $user->birth_date = $birth_date;
            }
            if ($training_date != "") {
                $user->training_date = $training_date;
            }

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
