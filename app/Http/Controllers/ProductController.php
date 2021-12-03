<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProductController extends BaseController
{

    public function index()
    {
        $data = Product::paginate();
        if ($data->total() > 0) {
            return response([
                "data" => $data->items(),
                "total" => $data->total(),
                "current_page" => $data->currentPage(),
                "next_page_url" => $data->nextPageUrl(),
            ]);
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
