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
        $data = User::where("city", $validated["pc"])
            ->where("karta", "!=", "")
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    public function dataPraktisi(Request $request)
    {
        $validated = $this->validate($request, [
            "pc" => "required",
            "pac" => "required",
        ]);

        $data = User::where("city", $validated["pc"])
            ->where("districts", $validated["pac"])
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    public function update($id, Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "required",
            "address" => "required",
        ]);
        $update = User::where("id", $id)->update([
            "name" => $validated["name"],
            "address" => $validated["address"],
        ]);
        return $this->actionResult($update, "praktisi_update");
    }
}
