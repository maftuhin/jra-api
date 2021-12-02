<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $news = News::select(["id", "title", "image", "link", "created_at"])
            ->orderBy("id", "DESC")
            ->limit(10)
            ->get();
        return response($news);
    }

    public function detail(Request $request)
    {
        $id = $request->input("id");
        $data = News::where("id", $id)->first();
        return response()->json($data);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $data = DB::table('news')->select(["id", "title", "image", "type", "link", "created_at"])
            ->where('title', 'LIKE', '%' . $query . '%')
            ->orderBy("id", "DESC")
            ->limit(30)
            ->get();

        if ($data->count() > 0) {
            return response()->json($data);
        } else {
            return response(["message" => "\"$query\"\nTidak ada Berita ditemukan"], 500);
        }
    }

    public function searchPaging(Request $request)
    {
        $query = $request->input('query');
        $data = DB::table('news')->select(["id", "title", "image", "type", "link", "created_at"])
            ->where('title', 'LIKE', '%' . $query . '%')
            ->orderBy("id", "DESC")
            ->paginate(10);

        if ($data->total() > 0) {
            return $this->pagingResponse($data);
        } else {
            return response(["message" => "\"$query\"\nTidak ada Berita ditemukan"], 500);
        }
    }

}
