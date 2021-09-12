<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\SkillUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class BannerController extends BaseController
{
    public function __construct()
    {
        
    }

    function index(Request $request, User $user)
    {
        $data = Banner::all();
        return response($data);
    }
}
