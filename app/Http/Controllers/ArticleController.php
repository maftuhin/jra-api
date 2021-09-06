<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class ArticleController extends BaseController
{

    function index(Request $request)
    {
        $query = $request->input("query");
        $type = $request->input("type");
        $article = Article::where("title", "LIKE", "%" . $query . "%")
            ->where("type", $type)
            ->simplePaginate(10);
        return $article;
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
