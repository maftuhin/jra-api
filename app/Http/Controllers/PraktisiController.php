<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PraktisiController extends Controller
{
    public function praktisiWithCard(Request $required)
    {
        $validated = $this->validate($required, [
            "pc" => "required",
            "pac" => "required",
        ]);
        $data = User::paginate(10);
        return $this->pagingResponse($data);
    }
}
