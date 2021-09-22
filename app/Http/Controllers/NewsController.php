<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class NewsController extends BaseController
{
    public function __construct()
    {
    }

    function index()
    {
        $news = News::select(["id", "title", "image", "link", "created_at"])
            ->orderBy("id", "DESC")
            ->limit(10)
            ->get();
        return response($news);
    }

    function detail(Request $request)
    {
        $id = $request->input("id");
        $data = News::where("id", $id)->first();
        return response()->json($data);
    }
}
