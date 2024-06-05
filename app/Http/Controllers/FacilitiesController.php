<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\Facility;
use App\Models\Log;
use App\Models\Role_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilitiesController extends Controller
{
    public function index(Request $request)
    {


        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();
        //dd($isRole);
        $facilities = Facility::orderBy('id', 'asc')->get();


        if ($isRole->role_type=="SuperAdmin" || $isRole->role_type=="Admin") {
            return view('facilities.index', compact(
                'dataLoginUser',
                'facilities',
                'isRole'
            ));
        }else{
            return redirect()->back();
        }

    }


    public function store(Request $request)
    {


        // ตรวจสอบว่าชื่อซ้ำหรือไม่
        $existing = Facility::where('name', $request->name)->first();
        if ($existing) {

            return response()->json([
                'message' => 'ชื่อนี้มีอยู่แล้ว'
            ], 400);
        }



        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'กรอก ชื่อเครื่องใช้ไฟฟ้า',
        ]);

        if ($validator->passes()) {

            $facilities = new Facility();
            $facilities->name = $request->name;
            $facilities->save();

            Log::addLog($request->user_id, '', 'Create Facility : ' . $request->name);

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

        $facilities = Facility::where('id', $id)->first();
        $useFacilities =  DB::connection('mysql')->table('facility_room')->where('facility_id', $id)->first();

        if ($useFacilities) {
            return response()->json([
                'message' => 'ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้อยู่'
            ], 400);
        } else {

            Log::addLog($user_id, '', 'Delete Facility ', $facilities->name);
            $facilities->delete($id);

            return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ'
            ], 201);
        }
    }

    public function edit(Request $request, $id)
    {
        $facilities = Facility::where('id', $id)->first();

        return response()->json($facilities, 200);
    }

    public function update(Request $request, $id)
    {
        $facilities = Facility::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            'name_edit' => 'required',
        ], [
            'name_edit.required' => 'กรอก ชื่อเฟอร์นิเจอร์',
        ]);

        if ($validator->passes()) {

            $facilities->name = $request->name_edit;
            $facilities->save();

            Log::addLog($request->user_id,'', 'Update Facility : ' . $request->name_edit);

            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }


    }
}
