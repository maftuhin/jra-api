<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        $data = Product::paginate();
        if ($data->total() > 0) {
            return $this->pagingResponse($data);
        } else {
            return response(["message" => "tidak ada data"], 500);
        }
    }

    public function detail($code)
    {
        $data = Product::where("code", $code)
            ->first();
        return response()->json($data, 200);
    }
}
