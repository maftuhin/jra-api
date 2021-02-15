<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AdminController extends BaseController
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
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Data Pengurus Kosong"], 500);
        }
    }

    public function province(Request $request)
    {
        $id = $request->input("id");
        $admin = Admin::select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("administratives.province", $id)
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Data Pengurus Kosong"], 500);
        }
    }

    public function city(Request $request)
    {
        $id = $request->input("id");
        $admin = Admin::select("users.id", "users.name", "admin_title.title", "administratives.jabatan")
            ->join("users", "administratives.member", "users.id")
            ->join("admin_title", "administratives.jabatan", "admin_title.id")
            ->where("administratives.city", $id)
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
            ->get();
        if ($admin->count() > 0) {
            return response()->json($admin);
        } else {
            return response()->json(["message" => "Data Pengurus Kosong"], 500);
        }
    }
}
