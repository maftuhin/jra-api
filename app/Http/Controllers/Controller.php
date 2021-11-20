<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    protected function pagingResponse($data)
    {
        return response()->json([
            "data" => $data->items(),
            "total" => $data->total(),
            "current_page" => $data->currentPage(),
            "next_page_url" => $data->nextPageUrl()
        ]);
    }

    protected function actionValidation($action, $message, $error)
    {
        if ($action == 1) {
            return response()->json(['message' => $message], 200);
        } else {
            return response()->json(['message' => $error], 500);
        }
    }
}
