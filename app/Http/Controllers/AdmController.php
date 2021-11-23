<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class AdmController extends Controller
{

    public function donation(Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
            "email" => "required",
            "phone" => "required",
            "donation" => "required|numeric"
        ], [
            "name.required" => "Nama Wajib Diisi"
        ]);

        $insert = Donation::insert([
            "name" => $validated["name"],
            "address" => $validated["address"],
            "phone" => $validated["phone"],
            "email" => $validated["email"],
            "donation" => $validated["donation"]
        ]);

        return $this->actionValidation($insert, "Success", "Terjadi Kesalahan");
    }
}
