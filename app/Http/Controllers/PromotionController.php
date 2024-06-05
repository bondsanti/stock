<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Session;
use App\Models\Project;
use App\Models\Promotion;
use App\Models\Role_user;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index(Request $request)
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        //dd($dataLoginUser);
        //permission sub by dept

        $isRole = Role_user::where('user_id', $dataLoginUser->id)->first();
        //dd($isRole);

        $promotions = Promotion::leftJoin('projects', 'projects.id', '=', 'promotions.project_id')
            ->select('promotions.*', 'projects.name')
            ->whereIn('show_channel', ['0', '1'])
            ->where('expire', '>=', Carbon::now()) // ใส่เงื่อนไขนี้เพื่อกรองเฉพาะที่ไม่หมดอายุ
            ->orderBy('id', 'desc')
            ->paginate(12);

        $promotionsExp= Promotion::leftJoin('projects', 'projects.id', '=', 'promotions.project_id')
            ->select('promotions.*', 'projects.name')
            ->whereIn('show_channel', ['0', '1'])
            ->orderBy('id', 'desc')
            ->paginate(12);


        $projects = Project::orderBy('name', 'asc')->get();

        return view('promotions.index', compact(
            'dataLoginUser',
            'promotions',
            'promotionsExp',
            'projects',
            'isRole'
        ));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'startdate' => 'required',
            'expire' => 'required',
            'project_id' => 'required',
        ], [
            'title.required' => 'กรอก ชื่อโปรโมชั่น',
            'startdate.required' => 'กรอก วันที่เริ่ม',
            'expire.required' => 'กรอก วันที่หมดอายุ',
            'project_id.required' => 'เลือก โครงการ',
        ]);

        if ($validator->passes()) {

            $promotion = new Promotion();
            $promotion->title = $request->title;
            $promotion->content = $request->content;
            $promotion->project_id = $request->project_id;
            $promotion->expire = $request->expire;
            $promotion->youtube_url = $request->youtube_url;
            $promotion->startdate = $request->startdate;
            $promotion->show_channel = $request->show_channel;
            $promotion->save();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('image', 'public');
                $promotion->image = config('app.url') . '/storage/' .  $path;
                $promotion->save();
            }

            // Log::addLog($request->user_id, '', 'Create Promotion : ' . $promotion);

            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();

        }else{

            return response()->json(['error' => $validator->errors()]);

        }

    }



    public function edit(Request $request, $id)
    {
        $promotion = Promotion::where('id', $id)->first();


        return response()->json($promotion, 200);
    }

    public function destroy(Request $request, $id, $user_id)
    {

        $promotion = Promotion::where('id', $id)->first();


        if ($promotion->image) {
            Storage::delete(str_replace(config('app.url') . '/storage/', '', $promotion->image));
        }

        Log::addLog($user_id, '', 'Delete Promotion : ' . $promotion);
        $promotion->delete($id);

        return response()->json([
            'message' => 'ลบข้อมูลสำเร็จ'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::where('id', $id)->first();
        $promotion_old = $promotion->toArray();


        $validator = Validator::make($request->all(), [
            'title_edit' => 'required',
            'startdate_edit' => 'required',
            'expire_edit' => 'required',
            'project_id' => 'required',
        ], [
            'title_edit.required' => 'กรอก ชื่อโปรโมชั่น',
            'startdate_edit.required' => 'กรอก วันที่เริ่ม',
            'expire_edit.required' => 'กรอก วันที่หมดอายุ',
            'project_id.required' => 'เลือก โครงการ',
        ]);

        if ($validator->passes()) {

            $promotion->title = $request->title_edit;
            $promotion->content = $request->content_edit;
            $promotion->project_id = $request->project_id;
            $promotion->expire = $request->expire_edit;
            $promotion->youtube_url = $request->youtube_url_edit;
            $promotion->startdate = $request->startdate_edit;
            $promotion->show_channel = $request->show_channel_edit;
            $promotion->save();

            if ($request->hasFile('image_edit')) {

                if ($promotion->image) {
                    Storage::delete(str_replace(config('app.url') . '/storage/', '', $promotion->image));
                }

                $path = $request->file('image_edit')->store('image', 'public');
                $promotion->image = config('app.url') . '/storage/' . $path;
            }

            $promotion->save();
            // ทำการ refresh ข้อมูลในโมเดล Floor เพื่อให้ข้อมูลเป็นข้อมูลที่มีอยู่ในฐานข้อมูล
            $promotion->refresh();

            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($request->user_id, json_encode($promotion_old), 'Update Promotion : ' . $promotion);



            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {

            return response()->json(['error' => $validator->errors()]);
        }
    }
}
