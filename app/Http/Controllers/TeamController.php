<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Log;
use App\Models\Role_user;
use App\Models\Room;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index(Request $request)
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();
        //dd($isRole);
        $teams = Team::orderBy('id', 'asc')->get();


        if ($isRole->role_type=="SuperAdmin" || $isRole->role_type=="Staff") {
            return view('teams.index', compact(
                'dataLoginUser',
                'teams',
                'isRole'
            ));
        }else{
            return redirect()->back();
        }
    }


    public function store(Request $request)
    {


        // ตรวจสอบว่าชื่อซ้ำหรือไม่
        $existing = Team::where('team_name', $request->team_name)->first();
        if ($existing) {

            return response()->json([
                'message' => 'ชื่อนี้มีอยู่แล้ว'
            ], 400);
        }



        $validator = Validator::make($request->all(), [
            'team_name' => 'required',
        ], [
            'team_name.required' => 'กรอก ชื่อทีม',
        ]);

        if ($validator->passes()) {

            $team = new Team();
            $team->team_name = $request->team_name;
            $team->save();

            Log::addLog($request->user_id, '', 'Create Team : ' . $request->team_name);

            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id, $user_id)
    {

        $team = Team::where('id', $id)->first();
        $useTeam = Room::where('agent', 'LIKE', '%' . $team->team_name . '%')->first();

        if ($useTeam) {
            return response()->json([
                'message' => 'ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้อยู่'
            ], 400);
        } else {

            Log::addLog($user_id, '', 'Delete Team : ', $team->team_name);
            $team->delete($id);

            return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ'
            ], 201);
        }
    }

    public function edit(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();

        return response()->json($team, 200);
    }

    public function update(Request $request, $id)
    {
        $team = Team::where('id', $id)->first();
        $team_old = $team->toArray();

        $validator = Validator::make($request->all(), [
            'team_name_edit' => 'required',
        ], [
            'team_name_edit.required' => 'กรอก ชื่อทีม',
        ]);

        if ($validator->passes()) {

            $team->team_name = $request->team_name_edit;
            $team->save();

            Log::addLog($request->user_id,json_encode($team_old), 'Update Team : ' . $team);

            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }


    }
}
