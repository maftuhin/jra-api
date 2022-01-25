<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Social;
use Illuminate\Http\Request;

class SocialController extends Controller
{

    public function index()
    {
        $data = Social::all();
        return response()->json($data);
    }

    function list($id) {
        $data = Link::where('social', $id)
            ->paginate(10);
        return $this->pagingResponse($data);
    }

    public function detail($id)
    {
        $data = Link::select("links.id", "links.name", "links.link", "socials.title")
            ->join("socials", "socials.id", "links.social")
            ->where("links.id", $id)
            ->first();
        return response()->json($data);
    }

    public function data(Link $data)
    {
        $me = auth()->user();
        $data = $data->newQuery();
        $data->select("links.id", "links.name", "links.link", "socials.title")
            ->join("socials", "socials.id", "links.social");
        $role = $me->role;
        if ($role == "PW") {
            $data->where("region", $me->province);
        } else if ($role == "PC") {
            $data->where("region", $me->city);
        } else {
            $data->where("region", 0);
        }
        $result = $data->paginate();
        return $this->pagingResponse($result);
    }

    public function store(Request $request)
    {
        $me = auth()->user();
        $role = $me->role;
        $validated = $this->validate($request, [
            "type" => "required",
            "link" => "required",
            "name" => "required",
        ]);
        $data = [
            "social" => $validated["type"],
            "link" => $validated["link"],
            "name" => $validated["name"],
        ];
        if ($role == "PW") {
            $data["region"] = $me->province;
        } else if ($role == "PC") {
            $data["region"] = $me->city;
        } else {
            $data["region"] = 0;
        }

        $store = Link::insert($data);
        return $this->actionResult($store, "input_social");
    }

    public function update($id, Request $request)
    {
        $validated = $this->validate($request, [
            "name" => "requeired",
            "link" => "required",
        ]);

        $update = Link::where("id", $id)
            ->update([
                "name" => $validated["name"],
                "link" => $validated["link"],
            ]);
        return $this->actionResult($update,"update_social");
    }

    public function delete($id)
    {
        $delete = Link::find($id)->delete();
        return $this->actionResult($delete, "delete_social");
    }
}
