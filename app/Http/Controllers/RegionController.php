<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;

class RegionController extends Controller
{

    public function getProvinsi()
    {
        $data = DB::table("wilayah_provinsi")
            ->select(["id", "name"])
            ->orderBy("name", "ASC")->get();
        return response()->json($data, 200);
    }

    public function district()
    {
        $data = DB::table("wilayah_kabupaten")
            ->select(["id", "provinsi_id as province_id", "name"])
            ->orderBy("name", "ASC")->get();
        return response()->json($data, 200);
    }

    public function getKecamatan()
    {
        $data = DB::table("wilayah_kecamatan")
            ->select(["id", "name", "kabupaten_id as city_id"])
            ->orderBy("name", "ASC")->get();
        return response()->json($data, 200);
    }

    public function getKabupaten($id)
    {
        $data = DB::table("wilayah_kabupaten")
            ->select(["id", "name"])
            ->where("provinsi_id", "=", $id)
            ->orderBy("name", "ASC")->get();
        return response()->json($data, 200);
    }
}
