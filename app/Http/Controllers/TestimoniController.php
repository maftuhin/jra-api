<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{

    public function index()
    {
        $data = Testimoni::orderBy('created_at', 'DESC')
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'rate' => 'required|numeric|min:1',
            'testimoni' => 'required',
            'anonymous' => 'required',
        ], [
            "name.required" => "Nama Wajib Diisi",
            "address.required" => "Alamat Wajib Diisi",
            "rate.min" => "Beri Bintang dari 1 sampai 5",
            "testimoni.required" => "Tuliskan Testimoni Anda",
        ]);
        $store = Testimoni::insert([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'rate' => $validated['rate'],
            'testimoni' => $validated['testimoni'],
            'anonymous' => $validated['anonymous'],
            'created_at' => Carbon::now(),
        ]);
        return $this->actionResult($store, "review_success");
    }
}
