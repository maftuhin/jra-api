<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class TestimoniController extends BaseController
{

    function index()
    {
        $data = Testimoni::select('id', 'name', 'anonymous', 'address', 'rate', 'testimoni', 'created_at')->get();
        return response()->json($data);
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
        return $store;
        // $data = Link::where('social', $id)->paginate(10);
        // return response([
        //     "data" => $data->items(),
        //     "total" => $data->total(),
        //     "current_page" => $data->currentPage(),
        //     "next_page_url" => $data->nextPageUrl()
        // ]);
    }
}
