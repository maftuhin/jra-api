<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ], 200);
    }

    protected function pagingResponse($data)
    {
        return response()->json([
            "data" => $data->items(),
            "total" => $data->total(),
            "current_page" => $data->currentPage(),
            "next_page_url" => $data->nextPageUrl(),
        ]);
    }

    protected function actionResult($action, $code)
    {
        if ($action == 1) {
            $data = DB::table("messages")
                ->select("message")
                ->where("code", $code)
                ->first();
            return response()->json($data, 200);
        } else {
            return response()->json(['message' => "Terjadi Kesalahan"], 500);
        }
    }
}
