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

    function search(Request $request)
    {
        $query = $request->input('query');
        $data = News::select(["id", "title", "image", "link", "created_at"])
            ->where('title', 'LIKE', '%' . $query . '%')
            ->orderBy("title", "ASC")
            ->get();

        if ($data->count() > 0) {
            return response()->json($data);
        } else {
            return response(["message" => "tidak ada data"], 500);
        }
    }
}
