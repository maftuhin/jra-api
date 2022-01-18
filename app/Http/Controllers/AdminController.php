<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
    }

    public function pusat()
    {
        $admin = Admin::select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("administratives.main", 1)
            ->orderBy('admin_title.id', 'ASC')
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Belum Ada Data Pengurus"], 500);
        }
    }

    public function province(Request $request)
    {
        $id = $request->input("id");
        $admin = Admin::select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("administratives.province", $id)
            ->orderBy('admin_title.id', 'ASC')
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Belum Ada Data Pengurus"], 500);
        }
    }

    public function city(Request $request)
    {
        $id = $request->input("id");
        $admin = Admin::select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("administratives.city", $id)
            ->orderBy('admin_title.id', 'ASC')
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Data Pengurus Kosong"], 500);
        }
    }

    public function district(Request $request)
    {
        $id = $request->input("id");
        $admin = Admin::select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("administratives.districts", $id)
            ->orderBy('admin_title.id', 'ASC')
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Data Pengurus Kosong"], 500);
        }
    }

    public function adminTitle()
    {
        $data = DB::table("admin_title")
            ->select("id", "title")
            ->get();
        return $data;
    }

    public function userAdmin(User $user)
    {
        $me = auth()->user();
        $role = $me->role;
        $user = $user->newQuery();
        $user->select("id", "name", "address");
        $user->orderBy("name", "ASC");

        if ($role == "PW") {
            $user->where("province", $me->province);
        } else if ($role == "PC") {
            $user->where("city", $me->city);
        } else if ($role == "PP") {
            // $user->where("administratives.main", 1);
        } else {
            return response()->json(
                ["message" => "Hanya Untuk Pengurus"],
                500
            );
        }
        $result = $user->paginate(20);
        return $this->pagingResponse($result);
    }

    public function data(Admin $admin)
    {
        $me = auth()->user();
        $admin = $admin->newQuery();
        $admin->select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->orderBy('admin_title.id', 'ASC');
        $role = $me->role;
        if ($role == "PW") {
            $admin->where("administratives.province", $me->province);
        } else if ($role == "PC") {
            $admin->where("administratives.city", $me->city);
        } else if ($role == "PP") {
            $admin->where("administratives.main", 1);
        } else {
            return response()->json(
                ["message" => "Hanya Untuk Pengurus"],
                500
            );
        }
        $result = $admin->get();
        return $result;
    }

    public function addAdmin(Request $request)
    {
        $me = auth()->user();
        $validated = $this->validate($request, [
            "user" => "requeired",
            "position" => "requeired",
        ]);
        $role = $me->role;
        if ($role == "PW") {

        } else if ($role == "PC") {

        } else if ($role == "PP") {
            
        } else {
            return response()->json(
                ["message" => "Hanya Untuk Pengurus"],
                500
            );
        }
    }
}
