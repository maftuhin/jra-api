<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ArticleController extends BaseController
{

    function index(Request $request)
    {
        $type = $request->input("type");
        $article = Article::select("articles.id", "articles.title", "articles.image")
            ->join("categories", "categories.id", "articles.category")
            ->where("categories.code", $type)
            ->paginate(10);
        if ($article->total() > 0) {
            return response([
                "data" => $article->items(),
                "total" => $article->total(),
                "current_page" => $article->currentPage(),
                "next_page_url" => $article->nextPageUrl()
            ]);
        } else {
            return response(["message" => "tidak ada data"], 500);
        }
    }

    public function detail($id)
    {
        $article = Article::where("id", "=", $id)->first();
        if ($article != null) {
            return response($article);
        } else {
            return response(["message" => "article tidak ditemukan"], 500);
        }
    }
}
