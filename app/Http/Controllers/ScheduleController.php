<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    function jadwalRuqyahMassal()
    {
        $data = Schedule::select("schedules.*")
            ->where("type", "massal")
            ->orderBy("tanggal", "ASC")
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    function jadwalPelatihanRuqyah()
    {
        $data = Schedule::select("schedules.*")
            ->where("type", "pelatihan")
            ->orderBy("tanggal", "ASC")
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    function jadwalBekToGur()
    {
        $data = Schedule::select("schedules.*")
            ->where("type", "btg")
            ->orderBy("tanggal", "ASC")
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    function store(Request $request)
    {
        $validated = $this->validate($request, [
            "type" => "required",
            "place" => "required",
            "pelaksana" => "required",
            "contact" => "required",
            "tanggal" => "required"
        ]);

        $store = Schedule::insert([
            "type" => $validated["type"],
            "place" => $validated["place"],
            "pelaksana" => $validated["pelaksana"],
            "contact" => $validated["contact"],
            "tanggal" => $validated["tanggal"]
        ]);
        return $this->actionValidation($store, "Input Jadwal Berhasil");
    }
}
