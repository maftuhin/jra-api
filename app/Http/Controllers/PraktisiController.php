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

    public function dataPraktisi(User $user, Request $request)
    {
        $user = $user->newQuery();
        $validated = $this->validate($request, [
            "pc" => "required",
            "pac" => "string",
        ]);

        $user->where("city", $validated["pc"]);
        if($validated["pac"]!=""){
            $user->where("districts", $validated["pac"]);
        }
        $result = $user->paginate();
        return $this->pagingResponse($result);
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

    public function registerKarta()
    {

    }
}
