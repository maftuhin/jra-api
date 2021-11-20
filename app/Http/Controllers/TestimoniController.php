<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{

    function index()
    {
        $data = Testimoni::orderBy('created_at', 'DESC')
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'rate' => 'required',
            'testimoni' => 'required',
            'anonymous' => 'required'
        ]);
        $store = Testimoni::insert([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'rate' => $validated['rate'],
            'testimoni' => $validated['testimoni'],
            'anonymous' => $validated['anonymous'],
            'created_at' => Carbon::now()
        ]);
        return $this->actionValidation($store, "Terima Kasih Reviewnya", "Terjadi Kesalahan");
    }
}
