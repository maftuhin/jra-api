<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    // Pendaftaran Pelatihan Ruqyah
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
            "phone" => "required",
            "email" => "required|email",
            "place" => "required",
            "type" => "required",
        ]);

        $store = DB::table("participant")->insert([
            "name" => $validated["name"],
            "address" => $validated["address"],
            "phone" => $validated["phone"],
            "email" => $validated["email"],
            "place" => $validated["place"],
            "type" => $validated["type"],
        ]);
        return $this->actionResult($store, $validated["type"]);
    }

}
