<?php

namespace App\Http\Controllers;

use App\Models\SkillUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['search', 'detail', 'praktisi']]);
    }

    public function search(Request $request, User $user)
    {
        $user = $user->newQuery();
        $user->select("id", "name", "address", "gender");
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

    public function praktisi(Request $request, User $user)
    {
        $user = $user->newQuery();
        $user->select("id", "name", "address", "gender");
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

    public function detail(Request $request)
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

    public function update(Request $request)
    {
        $token = auth()->user();
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        $gender = $request->input("gender");
        $karta = $request->input("karta");
        $email = $request->input("email");
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

            $user->name = $validated['name'];
            $user->address = $validated['address'];
            $user->phone = $validated["phone"];
            $user->piagam = $license;
            $user->karta = $karta;
            $user->gender = $gender;
            $user->skill = $skill;
            $user->profession = $profession;
            $user->birth_place = $birth_place;
            $user->training_place = $training_place;
            $user->phone_visibility = $phone_visibility;
            $user->email = $email;
            if ($birth_date != "") {
                $user->birth_date = $birth_date;
            }
            if ($training_date != "") {
                $user->training_date = $training_date;
            }

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                $storagePath = './images/profiles';
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
                "message" => "Unauthorized",
            ], 401);
        }
    }

    public function password(Request $request)
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

    public function userStatus()
    {
        $user = auth()->user();
        $adm = User::select("users.id", "users.role", "title")
            ->leftJoin("administratives", "administratives.member", "users.id")
            ->leftJoin("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("users.id", $user->id)
            ->first();
        return $adm;
    }
}
