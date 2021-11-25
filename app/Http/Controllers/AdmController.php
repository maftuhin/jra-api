<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Suggest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdmController extends Controller
{

    // Donasi
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

        return $this->actionValidation($insert, "Success");
    }

    //Suggest
    function suggest(Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
            "phone" => "required",
            "email" => "required|email",
            "suggest" => "required"
        ]);

        $store = Suggest::insert([
            "name" => $validated["name"],
            "address" => $validated["address"],
            "phone" => $validated["phone"],
            "email" => $validated["email"],
            "suggest" => $validated["suggest"],
            "created_at" => Carbon::now()
        ]);

        return $this->actionValidation($store, "Terima Kasih Atas kritik dan saranya");
    }
}