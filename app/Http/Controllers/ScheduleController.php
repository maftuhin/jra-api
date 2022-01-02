<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input("type");
        $data = Schedule::select("schedules.id", "schedules.tanggal", "schedules.place", "schedules.contact", "users.name", "wilayah_kabupaten.name as organizer")
            ->join("users", "users.id", "schedules.user")
            ->join("wilayah_kabupaten", "wilayah_kabupaten.id", "schedules.organizer")
            ->where("type", $type)
            ->orderBy("tanggal", "ASC")
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    public function show($id)
    {
        $data = Schedule::where("id", $id)->first();
        return response($data);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $this->validate($request, [
            "type" => "required",
            "place" => "required",
            "pelaksana" => "required",
            "contact" => "required",
            "tanggal" => "required",
        ], [
            "tanggal.required" => "Isi Tanggal Pelaksaanaan Terlebih Dahulu",
            "place.required" => "Tempat Pelaksanaan Wajib Diisi",
            "pelaksana.required" => "Isi nama PC/PW",
            "contact.required" => "Kontak Panitia Wajib Diisi",
        ]);

        $store = Schedule::insert([
            "type" => $validated["type"],
            "tanggal" => $validated["tanggal"],
            "place" => $validated["place"],
            "organizer" => $user->city,
            "contact" => $validated["contact"],
            "user" => $user->id,
        ]);
        return $this->actionResult($store, "schedule_input");
    }

    public function update($id, Request $request)
    {
        $validated = $this->validate($request, [
            "type" => "required",
            "place" => "required",
            "pelaksana" => "required",
            "contact" => "required",
            "tanggal" => "required",
        ], [
            "tanggal.required" => "Isi Tanggal Pelaksaanaan Terlebih Dahulu",
            "place.required" => "Tempat Pelaksanaan Wajib Diisi",
            "pelaksana.required" => "Isi nama PC/PW",
            "contact.required" => "Kontak Panitia Wajib Diisi",
        ]);

        $update = Schedule::where("id", $id)->update([
            "type" => $validated["type"],
            "place" => $validated["place"],
            "pelaksana" => $validated["pelaksana"],
            "contact" => $validated["contact"],
            "tanggal" => $validated["tanggal"],
        ]);
        return $this->actionResult($update, "schedule_update");
    }

    public function category()
    {
        $data = DB::table("schedule_category")
            ->select("id","category", "code")
            ->get();
        return response()->json($data);
    }

    public function destroy($id)
    {
        $delete = Schedule::where("id", $id)
            ->delete();
        return $this->actionResult($delete, "schedule_delete");
    }
}
