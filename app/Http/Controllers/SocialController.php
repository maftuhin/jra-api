<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Social;
use Laravel\Lumen\Routing\Controller as BaseController;

class SocialController extends BaseController
{

    function index()
    {
        $data = Social::all();
        return response()->json($data);
    }

    function list($id)
    {
        $data = Link::where('social', $id)->paginate(10);
        return response([
            "data" => $data->items(),
            "total" => $data->total(),
            "current_page" => $data->currentPage(),
            "next_page_url" => $data->nextPageUrl()
        ]);
    }
}
