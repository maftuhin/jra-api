<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\SkillUser;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    function getSkill()
    {
        $user = auth()->user();
        $data = Skill::all();
        $skill = SkillUser::select("skills.*", "skill_users.status")
            ->join("skills", "skills.id", "=", "skill_users.skill")
            ->where("user", $user->id)
            ->get();

        foreach ($data as $value) {
            $a = collect($skill)->where("id", $value["id"])->first();
            $value["status"] = $a["status"];
        }
        return response()->json($data);
    }

    function updateSkill(Request $request)
    {
        $user = auth()->user();
        $data = json_decode($request->input("skill"), true);

        foreach ($data as $value) {
            $skill = SkillUser::firstOrNew(['user' => $user->id, 'skill' => $value["id"]]);
            $skill->status = $value["status"];
            $skill->skill = $value["id"];
            $skill->save();
        }
        return response()->json(["message" => "sukses"]);
    }
}
