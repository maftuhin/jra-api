<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Suggest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            "donation" => "required|numeric",
            "photo" => "required",
        ], [
            "name.required" => "Nama Wajib Diisi",
            "address.required" => "Alamat Wajib Diisi",
            "email.required" => "Email Wajib Diisi",
            "phone.required" => "No. HP Wajib Diisi",
            "donation.required" => "Jumlah Donasi Harus Diisi",
            "photo.required" => "Upload Bukti Donasi",
        ]);

        $image = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $storagePath = './images/donation';
            $fileExtension = $photo->getClientOriginalExtension();
            $imageFileName = $validated["phone"] . '_' . Carbon::now() . '.' . $fileExtension;

            $fileOutPut = $photo->move($storagePath, $imageFileName);
            if (is_file($fileOutPut)) {
                $image = url() . '/images/donation/' . $imageFileName;
            }
        }

        $insert = Donation::insert([
            "name" => $validated["name"],
            "address" => $validated["address"],
            "phone" => $validated["phone"],
            "email" => $validated["email"],
            "donation" => $validated["donation"],
            "image" => $image,
            "created_at" => Carbon::now(),
        ]);

        return $this->actionResult($insert, "donation_success");
    }

    //Suggest
    public function suggest(Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
            "phone" => "required",
            "email" => "required|email",
            "suggest" => "required",
        ], [
            "name.required" => "Nama Wajib Diisi",
            "address.required" => "Alamat Wajib Diisi",
            "email.required" => "Email Wajib Diisi",
            "phone.required" => "No. HP Wajib Diisi",
            "suggest.required" => "Isi Kritik dan Saran Anda",
        ]);

        $store = Suggest::insert([
            "name" => $validated["name"],
            "address" => $validated["address"],
            "phone" => $validated["phone"],
            "email" => $validated["email"],
            "suggest" => $validated["suggest"],
            "created_at" => Carbon::now(),
        ]);

        return $this->actionResult($store, "suggest_success");
    }

    public function bank()
    {
        $data = DB::table("bank")
            ->select("bank", "account", "name")
            ->get();
        return $data;
    }

    public function inputIanah(Request $request)
    {
        $validated = $this->validate($request, [
            "pelaksana" => "required",
            "tanggal_pelaksanaan" => "required",
            "tempat_pelatihan" => "required",
            "jumlah_peserta" => "required",
            "ianah_peserta" => "required",
            "ianah_syahadah" => "required",
        ]);
        return $validated;
    }
}
