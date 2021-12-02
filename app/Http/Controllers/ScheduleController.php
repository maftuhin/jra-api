<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    function index(Request $request)
    {
        $type = $request->input("type");
        $data = Schedule::select("schedules.*")
            ->where("type", $type)
            ->orderBy("tanggal", "ASC")
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    function show($id)
    {
        $data = Schedule::where("id", $id)->first();
        return response($data);
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
        return $this->actionResult($store, "schedule_input");
    }
}
