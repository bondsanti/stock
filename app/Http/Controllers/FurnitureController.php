<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Furniture;
use App\Models\Log;
use App\Models\Role_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FurnitureController extends Controller
{
    public function index(Request $request)
    {

        // $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $dataLoginUser = Session::get('loginId');
        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);
        $furnitures = Furniture::orderBy('id', 'asc')->get();


        if ($isRole->role_type=="SuperAdmin" || $isRole->role_type=="Admin") {
            return view('furniture.index', compact(
                'dataLoginUser',
                'furnitures',
                'isRole'
            ));
        }else{
            return redirect()->back();
        }
    }


    public function store(Request $request)
    {


        // ตรวจสอบว่าชื่อซ้ำหรือไม่
        $existing = Furniture::where('name', $request->name)->first();
        if ($existing) {

            return response()->json([
                'message' => 'ชื่อนี้มีอยู่แล้ว'
            ], 400);
        }



        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'กรอก ชื่อเฟอร์นิเจอร์',
        ]);

        if ($validator->passes()) {

            $furnitures = new Furniture();
            $furnitures->name = $request->name;
            $furnitures->save();

            Log::addLog($request->user_id, '', 'Create Furniture : ' . $request->name);

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

        $furnitures = Furniture::where('id', $id)->first();
        $useFurniture =  DB::connection('mysql')->table('furniture_room')->where('furniture_id', $id)->first();

        if ($useFurniture) {
            return response()->json([
                'message' => 'ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้อยู่'
            ], 400);
        } else {

            Log::addLog($user_id, '', 'Delete Furniture : ', $furnitures->name);
            $furnitures->delete($id);

            return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ'
            ], 201);
        }
    }

    public function edit(Request $request, $id)
    {
        $furnitures = Furniture::where('id', $id)->first();

        return response()->json($furnitures, 200);
    }

    public function update(Request $request, $id)
    {
        $furnitures = Furniture::where('id', $id)->first();
        $furnitures_old = $furnitures->toArray();

        $validator = Validator::make($request->all(), [
            'name_edit' => 'required',
        ], [
            'name_edit.required' => 'กรอก ชื่อเฟอร์นิเจอร์',
        ]);

        if ($validator->passes()) {

            $furnitures->name = $request->name_edit;
            $furnitures->save();

            Log::addLog($request->user_id,json_encode($furnitures_old), 'Update Furniture : ' . $furnitures);

            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }


    }
}
