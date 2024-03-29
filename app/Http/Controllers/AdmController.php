<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Ianah;
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
        $me = auth()->user();
        $validated = $this->validate($request, [
            "tanggal_pelaksanaan" => "required",
            "tempat_pelatihan" => "required",
            "jumlah_peserta" => "required",
            "ianah_peserta" => "required",
            "ianah_syahadah" => "required",
            "code" => "required",
            "photo" => "required",
        ], [
            "tempat_pelatihan.required" => "Tempat Pelatihan Wajib Diisi",
            "jumlah_peserta.required" => "Jumlah Peserta Wajib Diisi",
            "ianah_peserta.required" => "Ianah Peserta Wajib Diisi",
            "ianah_syahadah.required" => "Ianah Syahadah Wajib Diisi",
            "photo.required" => "Upload Bukti Photo Terlebih Dahulu",
        ]);
        $data = [
            "tanggal" => $validated["tanggal_pelaksanaan"],
            "tempat" => $validated["tempat_pelatihan"],
            "jumlah_peserta" => $validated["jumlah_peserta"],
            "ianah_peserta" => $validated["ianah_peserta"],
            "ianah_syahadah" => $validated["ianah_syahadah"],
            "code" => $validated["code"],
            "created_at" => Carbon::now()->timezone("Asia/Jakarta"),
        ];
        $imageName = "";
        if ($me->role == "PW") {
            $data["province"] = $me->province;
            $imageName = $me->province;
        } else {
            $data["city"] = $me->city;
            $imageName = $me->city;
        }

        if ($request->hasFile("photo")) {
            $photo = $validated["photo"];
            $path = "/images/ianah/" . $validated["code"];
            $storagePath = '.' . $path;
            $fileExtension = $photo->getClientOriginalExtension();
            $imageFileName = $imageName . '_' . Carbon::now() . '.' . $fileExtension;

            $fileOutPut = $photo->move($storagePath, $imageFileName);
            if (is_file($fileOutPut)) {
                $data["image"] = url() . $path . '/' . $imageFileName;
            }
        }

        $store = Ianah::insert($data);
        return $this->actionResult($store, "input_ianah");
    }
}
