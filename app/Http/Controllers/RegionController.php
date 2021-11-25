<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getKabupaten(Request $request)
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
            ->where("visibility", 1)
            ->orderBy("name", "ASC")
            ->get();
        return response()->json($data, 200);
    }

    public function searchCity(Request $request)
    {
        $query = $request->input("q");
        $data = DB::table("wilayah_kabupaten as c")
            ->select(["c.id", "c.provinsi_id as province_id", "c.name", "c.code", "p.name as province"])
            ->join("wilayah_provinsi as p", "p.id", "c.provinsi_id")
            ->where("c.name", "LIKE", "%" . $query . "%")
            ->where("c.visibility", 1)
            ->orderBy("c.name", "ASC")
            ->paginate();

        if ($data->total() > 0) {
            return $this->pagingResponse($data);
        } else {
            return response(["message" => "tidak ada data"], 500);
        }
    }
}
