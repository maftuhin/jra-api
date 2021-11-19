<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Laravel\Lumen\Routing\Controller as BaseController;

class BannerController extends BaseController
{
    public function __construct()
    {
        
    }

    function index()
    {
        $data = Banner::all();
        return response($data);
    }
}
