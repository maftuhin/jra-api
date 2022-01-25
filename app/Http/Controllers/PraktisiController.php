<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PraktisiController extends Controller
{
    public function praktisiWithCard(User $user, Request $request)
    {
        $user = $user->newQuery();
        $pc = $request->input("pc");
        $pac = $request->input("pac");

        $user->select("id", "name", "address", "karta", "gender");
        $user->where("city", $pc);
        if ($pac != "") {
            $user->where("districts", $pac);
        }
        $user->where("karta", "!=", "");
        $result = $user->paginate();
        return $this->pagingResponse($result);
    }

    public function dataPraktisi(User $user, Request $request)
    {
        $user = $user->newQuery();
        $validated = $this->validate($request, [
            "pc" => "required",
            "pac" => "string",
        ]);

        $user->where("city", $validated["pc"]);
        if ($validated["pac"] != "") {
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
            "birth_place" => "required",
            "birth_date" => "required",
            "gender" => "required",
            "phone" => "required",
            "karta" => "",
            "skill" => "",
            "email" => "required|email",
            "license" => "required",
            "training_place" => "required",
            "training_date" => "required",
            "job" => "required",
        ]);
        $update = User::where("id", $id)->update([
            "name" => $validated["name"],
            "address" => $validated["address"],
            "birth_place" => $validated["birth_place"],
            "birth_date" => $validated["birth_date"],
            "gender" => $validated["gender"],
            "phone" => $validated["phone"],
            "karta" => $validated["karta"],
            "skill" => $validated["skill"],
            "email" => $validated["email"],
            "piagam" => $validated["license"],
            "training_place" => $validated["training_place"],
            "training_date" => $validated["training_date"],
            "profession" => $validated["job"],
        ]);
        return $this->actionResult($update, "praktisi_update");
    }

    public function registerKarta(Request $request)
    {
        $me = auth()->user();
        $request->merge(["id" => $me->id]);

        $validated = $this->validate($request, [
            "id" => "required|unique:card_request,user",
        ], [
            "id.unique" => "Anda Sudah Mengajukan Kartu Anggota, Mohon Menunggu",
        ]);
        $store = DB::table("card_request")->insert([
            "user" => $validated["id"],
            "status" => 0,
        ]);
        return $this->actionResult($store, "request_karta");
    }

    public function dataCardRequest()
    {
        $data = DB::table("card_request")
            ->select("users.id", "users.name", "users.address")
            ->join("users", "users.id", "card_request.user")
            ->paginate();
        return $this->pagingResponse($data);
    }
}
