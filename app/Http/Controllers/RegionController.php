<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;

class RegionController extends Controller
{

    public function getProvinsi()
    {
        $data = DB::table("wilayah_provinsi")
            ->select(["id", "name", "code"])
            ->where("visibility", 1)
            ->orderBy("name", "ASC")
            ->get();
        return response()->json($data, 200);
    }

    public function district(Request $request)
    {
        $id = $request->input("id");
        $data = DB::table("wilayah_kabupaten")
            ->select(["id", "provinsi_id as province_id", "name", "code"])
            ->where("provinsi_id", $id)
            ->where("visibility", 1)
            ->orderBy("name", "ASC")->get();
        return response()->json($data, 200);
    }

    public function getKecamatan(Request $request)
    {
        $id = $request->input("id");
        $data = DB::table("wilayah_kecamatan")
            ->select(["id", "name", "kabupaten_id as city_id", "code"])
            ->where("kabupaten_id", $id)
            ->orderBy("name", "ASC")->get();
        return response()->json($data, 200);
    }
}
