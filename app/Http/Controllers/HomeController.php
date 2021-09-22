<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => []]);
    }

    function banner()
    {
        $banner = DB::table("banners")->get();
        if ($banner->count() > 0) {
            return response($banner);
        } else {
            return response([
                "message" => "No Data Available"
            ], 500);
        }
    }

    function news()
    {
        $news = DB::table('news')
            ->select(["id", "title", "image","type", "link", "created_at"])
            ->orderBy("id", "DESC")
            ->limit(10)
            ->get();
        return response($news);
    }
}
