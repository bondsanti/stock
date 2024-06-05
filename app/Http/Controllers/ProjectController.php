<?php

namespace App\Http\Controllers;

use Session;
use DB;
use App\Models\Contract;
use App\Models\File_Price;
use App\Models\Floor;
use App\Models\Log;
use App\Models\Plan;
use App\Models\Project;
use App\Models\Role_user;
use App\Models\Room;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();
        //dd($isRole);

        if($isRole->role_type == 'Partner' || $isRole->role_type =='User' || $isRole->role_type =='Sale')
        {
            $project = Project::orderBy('name', 'asc')
            // ->when($dataLoginUser->low_rise == 1, function ($query) {
            //     return $query->where('low_rise', 1);
            // })
            // ->when($dataLoginUser->high_rise == 1, function ($query) {
            //     return $query->orWhere('high_rise', 1);
            // })
            ->where('active', 1)->get();
            $count_isActive = $project->count();
        }else{
            $project = Project::orderBy('name', 'asc')
            // ->when($dataLoginUser->low_rise == 1, function ($query) {
            //     return $query->where('low_rise', 1);
            // })
            // ->when($dataLoginUser->high_rise == 1, function ($query) {
            //     return $query->orWhere('high_rise', 1);
            // })
            ->when($isRole->role_type != 'SuperAdmin' && $isRole->role_type != 'Admin' && $isRole->role_type != 'Staff', function ($query) {
                return $query->where('active', 1);
            })
            ->get();
        }





        // dd($project);

        $active = Project::where('active', 1)->count();
        $unactive = Project::where('active', 0)->count();
        $hidden = Project::where('active', 2)->count();
        $all = Project::count();

        if ($isRole->role_type == "Partner") {
            return view('projects.partner.index', compact(
                'dataLoginUser',
                'active',
                'unactive',
                'all',
                'hidden',
                'project',
                'isRole',
                'count_isActive'
            ));
        }else{
            return view('projects.index', compact(
                'dataLoginUser',
                'active',
                'unactive',
                'all',
                'hidden',
                'project',
                'isRole'
            ));
        }
    }

    public function store(Request $request)
    {

        // ตรวจสอบว่าชื่อโครงการซ้ำหรือไม่
        $existingProject = Project::where('name', $request->name)->where('active',1)->first();
        if ($existingProject) {

            return response()->json([
                'message' => 'ชื่อโครงการนี้มีอยู่แล้ว'
            ], 400);

        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'กรอก ชื่อโครงการ',
        ]);


        if ($validator->passes()) {

            $project = new Project();
            $project->name = $request->name;
            $project->active = $request->active;
            $project->user_id = $request->user_id;
            $project->save();

            $projects = Project::latest()->first();

            $plans = DB::table('plans')->insert([
                'project_id' => $projects->id,
                'name' => $projects->name,
                'area' => 0,
                'bed_room' => 0,
                'bath_room' => 0,
                'living_room' => 0,
                'kitchen_room'=> 0
            ]);

            $exitPro =  DB::connection('mysql_report')->table('project')->where('Project_Name', 'LIKE', '%' . $request->name . '%')->count();

            if ($exitPro == 0) {
                //ลง DB Report
                DB::connection('mysql_report')->table('project')->insert([
                    'pid' => $projects->id,
                    'Project_Name' => $projects->name,
                    'Active' => 1,
                ]);
            }

            $project->refresh();

            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($request->user_id, '', 'Create Project : ' . $project);


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

        $project = Project::where('id', $id)->first();
        $useProject = Room::where('project_id', $id)->first();

        //dd($user);
        if ($useProject) {
            return response()->json([
                'message' => 'ไม่สามารถลบข้อมูลได้ เนื่องจากมีการใช้อยู่'
            ], 400);
        } else {

            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($user_id, '', 'Delete : ' . $project);

            $project->delete($id);
            DB::connection('mysql_report')->table('project')->where('pid', $id)->delete();



            return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ'
            ], 201);
        }
    }

    public function predelete(Request $request, $id, $user_id)
    {

		$project = Project::where('id', $id)->first();
        $project_old = $project->toArray();

		$project->active = "0";
		$project->low_rise = "0";
		$project->high_rise = "0";
		$project->save();

        $project->refresh();

        // สร้าง log การ insert โดยใช้ addLog
        Log::addLog($user_id, json_encode($project_old), 'Update Project : ' . $project);

        return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ!'
            ], 201);

    }

    public function detail($id)
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();
        $project = Project::where('id', $id)->first();
        $plans = Plan::where('project_id', $id)->get();
        $floors = Floor::where('project_id', $id)->get();
        $files = File_Price::where('project_id', $id)->latest()->first();



        return view('projects.detail', compact(
            'dataLoginUser',
            'project',
            'plans',
            'floors',
            'isRole',
            'files'
        ));
    }

    public function edit($id)
    {
        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();
        $project = Project::where('id', $id)->first();
        $contracts = Contract::where('project_id',$id)->get();

        $plans = Plan::where('project_id', $id)->get();
        $floors = Floor::where('project_id', $id)->get();

        $files = File_Price::where('project_id',$id)->orderBy('id', 'desc')->get();

        return view('projects.edit', compact(
            'dataLoginUser',
            'project',
            'plans',
            'floors',
            'contracts',
            'isRole',
            'files'
        ));
    }

    public function updateinfo(Request $request)
    {

        //dd($request->all());
        $project = Project::where('id', $request->project_id)->first();
        $project_old = $project->toArray();


        $project->name = $request->name;
        $project->address = $request->address;
        $project->phone = $request->phone;
        $project->description = $request->description;
        $project->start_price = $request->start_price;
        $project->active = $request->active;
        $project->area = $request->area;
        $project->unit = $request->unit;
        $project->building = $request->building;
        $project->type_project = $request->type_project;
        $project->user_id = $request->user_id;
        $project->map = $request->map;
        $project->low_rise = ($request->low_rise) ? $request->low_rise : '0';
        $project->high_rise = ($request->high_rise) ? $request->high_rise : '0';
        $project->urllink = $request->urllink;
        $project->save();


        if ($request->hasFile('logo')) {

            // ลบไฟล์รูปเก่า (หากมี)
            if ($project->logo) {
                $existingImage = basename(parse_url($project->logo, PHP_URL_PATH));

                $delURl= asset('uploads/logo/' . $existingImage);
                Storage::delete($delURl);

            }

            $image = $request->file('logo');
            $imageName = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension(); // สร้างชื่อไฟล์ใหม่แบบ UUID
            $image->move(public_path('uploads/logo/'), $imageName);
            $imageUrl = asset('uploads/logo/' . $imageName);
            $project->logo = $imageUrl;
            $project->save();
        }


        // $exitPro =  DB::connection('mysql_report')->table('project')->where('Project_Name', 'LIKE', '%' . $request->name . '%')->count();

        // if ($exitPro == 0) {

        //     DB::connection('mysql_report')->table('project')
        //     ->where('pid', $request->project_id)
        //     ->update([
        //         'Project_Name' => $request->name,
        //         'address_full' => $request->address,
        //     ]);
        // }

       // สร้าง log การ insert โดยใช้ addLog
        Log::addLog($request->user_id,json_encode($project_old), 'Update Project : ' . $project);

        Alert::success('Success', 'อัพเดทข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function updateplan(Request $request)
    {

        $plan = Plan::where('id', $request->plan_id)->first();
        $plan_old = $plan->toArray();

        $plan->area = $request->area;
        $plan->name = $request->name;
        $plan->bed_room = $request->bed_room;
        $plan->bath_room = $request->bath_room;
        $plan->living_room = $request->living_room;
        $plan->kitchen_room = $request->kitchen_room;



        if ($request->hasFile('plan_image')) {

            if ($plan->plan_image) {
                $existingImage = basename(parse_url($plan->plan_image, PHP_URL_PATH));
                Storage::delete('uploads/plan/' . $existingImage);
            }

            $image = $request->file('plan_image');
            $imageName = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension(); // สร้างชื่อไฟล์ใหม่แบบ UUID

            $image->move(public_path('uploads/plan/'), $imageName);
            $imageUrl = asset('uploads/plan/' . $imageName);
            $plan->plan_image = $imageUrl;
            $plan->save();
        }


        $plan->save();
        // ทำการ refresh ข้อมูลในโมเดล Floor เพื่อให้ข้อมูลเป็นข้อมูลที่มีอยู่ในฐานข้อมูล
        $plan->refresh();

        // สร้าง log การ insert โดยใช้ addLog
        Log::addLog($request->user_id, json_encode($plan_old), 'Update : ' . $plan);


        //dd($plan);
        Alert::success('Success', 'อัพเดทข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function updatefloor(Request $request)
    {

        $floor = Floor::where('id', $request->floor_id)->first();
        $floor_old = $floor->toArray();

        $floor->name = $request->name;
        $floor->floor = $request->floor;
        $floor->building = $request->building;
        $floor->rooms = $request->rooms;

        if ($request->hasFile('images')) {

            if ($floor->image) {
                $existingImage = basename(parse_url($floor->image, PHP_URL_PATH));
                Storage::delete('uploads/floor/' . $existingImage);
            }

            $image = $request->file('images');
            $imageName = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension(); // สร้างชื่อไฟล์ใหม่แบบ UUID

            $image->move(public_path('uploads/floor/'), $imageName);
            $imageUrl = asset('uploads/floor/' . $imageName);
            $floor->image = $imageUrl;
            $floor->save();
        }


        $floor->save();
        // ทำการ refresh ข้อมูลในโมเดล Floor เพื่อให้ข้อมูลเป็นข้อมูลที่มีอยู่ในฐานข้อมูล
        $floor->refresh();

        // สร้าง log การ insert โดยใช้ addLog
        Log::addLog($request->user_id, json_encode($floor_old), 'Update : ' . $floor);


        //dd($plan);
        Alert::success('Success', 'อัพเดทข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function storeplan(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'bath_room' => 'required',
                'area' => 'required',
                'living_room' => 'required',
                'bed_room' => 'required',
                'kitchen_room' => 'required',

            ],
            [
                'name.required' => 'กรอก ชื่อห้องชุด',
                'bath_room.required' => 'กรอก จำนวนห้องน้ำ',
                'area.required' => 'กรอก จำนวนพื้นที่',
                'living_room.required' => 'กรอก จำนวนห้องนั่งเล่น',
                'bed_room.required' => 'กรอก จำนวนห้องนอน',
                'kitchen_room.required' => 'กรอก จำนวนห้องครัว',
            ]
        );

        if ($validator->passes()) {
            $plan = new Plan();
            $plan->area = $request->area;
            $plan->name = $request->name;
            $plan->bed_room = $request->bed_room;
            $plan->bath_room = $request->bath_room;
            $plan->living_room = $request->living_room;
            $plan->kitchen_room = $request->kitchen_room;
            $plan->project_id = $request->project_id;


            if ($request->hasFile('plan_image')) {
                $image = $request->file('plan_image');
                $filename = $image->getClientOriginalName();
                $path = $image->storeAs('plan_image', $filename, 'public');
                $plan->plan_image = $filename;
                $plan->save();
            }

            $plan->save();


            $plan->refresh();

            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($request->user_id, '', 'Create : ' . $plan);

            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ',
                'id' => $request->project_id
            ], 201);


            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function storefloor(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'floor' => 'required',
                'building' => 'required',
                'rooms' => 'required',

            ],
            [
                'name.required' => 'กรอก ชื่อ',
                'floor.required' => 'กรอก ชั้น',
                'building.required' => 'กรอก ตึก',
                'rooms.required' => 'กรอก จำนวนห้อง',
            ]
        );

        if ($validator->passes()) {
            $floor = new Floor();
            $floor->name = $request->name;
            $floor->floor = $request->floor;
            $floor->building = $request->building;
            $floor->project_id = $request->project_id;
            $floor->rooms = $request->rooms;


            if ($request->hasFile('image')) {


                $image = $request->file('image');
                $imageName = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension(); // สร้างชื่อไฟล์ใหม่แบบ UUID

                $image->move(public_path('uploads/floor/'), $imageName);
                $imageUrl = asset('uploads/floor/' . $imageName);
                $floor->image = $imageUrl;
                $floor->save();
            }

            $floor->save();

            // ทำการ refresh ข้อมูลในโมเดล Floor เพื่อให้ข้อมูลเป็นข้อมูลที่มีอยู่ในฐานข้อมูล
            $floor->refresh();

            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($request->user_id, '', 'Create : ' . $floor);



            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ',
                'id' => $request->project_id
            ], 201);



            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function storeFilePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exp' => 'required',
            'file' => 'required|mimes:pdf|max:10240',
            'email_alert.*' => 'required|email',
        ], [
            'exp.required' => 'กรอก วันที่หมดอายุ',
            'file.mimes' => 'กรุณา เลือกไฟล์ pdf',
            'email_alert.*.required' => 'กรุณากรอกอีเมล์',
            'email_alert.*.email' => 'กรุณากรอกอีเมล์ให้ถูกต้อง',
        ]);

        if ($validator->passes()) {

                $files = new File_Price();
                $files->project_id = $request->project_id;
                $files->exp = $request->exp;
                $files->remark = $request->remark;
                $files->created_by = $request->user_id;
                $files->email_alert = $request->email_alert;
                $files->save();

                if ($request->hasFile('file')) {
                    $image = $request->file('file');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/file-price/'), $imageName);
                    $imageUrl = asset('uploads/file-price/' . $imageName);
                    $files->file = $imageUrl;
                    $files->save();
                }


                $files->refresh();
                // สร้าง log การ insert โดยใช้ addLog
                Log::addLog($request->user_id, '', 'Create FilePrice: ' . $files);
                return response()->json(['message' => 'เพิ่มข้อมูลสำเร็จ','id'=>$request->project_id], 201);


        }else{
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function updateFilePrice(Request $request,$id)
    {
        $file = File_Price::where('id', $id)->first();
        $file_old = $file->toArray();

        $validator = Validator::make($request->all(), [
            'exp_edit' => 'required',
        ], [
            'exp_edit.required' => 'กรอก วันที่หมดอายุ',
        ]);
        //กรองค่า null ออกจาก array
        $emailArray = array_values(array_filter($request->email_edit));

        // if (!empty($emailArray)) {
        //     $validEmail = reset($emailArray); // เลือกค่า email แรกจาก array
        // }
//dd($emailArray);

        if ($validator->passes()) {

            $file->exp = $request->exp_edit;
            $file->remark = $request->remark_edit;
            $file->updated_by = $request->user_id;
            $file->email_alert = $emailArray;




            if ($request->hasFile('file_edit')) {

                if ($file->file) {
                    $existingImage = basename(parse_url($file->file, PHP_URL_PATH));
                    Storage::delete('uploads/file-price/' . $existingImage);
                }

                $image = $request->file('file_edit');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/file-price/'), $imageName);
                $imageUrl = asset('uploads/file-price/' . $imageName);
                $file->file = $imageUrl;
                $file->save();
            }


            $file->save();

            $file->refresh();

            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($request->user_id, json_encode($file_old), 'Update : ' . $file);
            return response()->json(['message' => 'อัพเดทข้อมูลสำเร็จ','id'=>$request->project_id], 201);

        }else{
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function editFilePrice($id)
    {
        $files = File_Price::where('id',$id)->first();
        return response()->json($files, 200);
    }

    public function destroyplan(Request $request)
    {

        $plan = Plan::where('id', $request->plan_id)->first();

        //dd($plan);

        Log::addLog($request->user_id, '', 'Delete Plan : ', $plan->name);


        // if ($plan->plan_image) {
        //     Storage::delete(str_replace(config('app.url') . '/storage/', '', $plan->plan_image));
        // }
        if ($plan->plan_image) {
            Storage::delete('plan/' . $plan->image);
        }



        $plan->delete($request->plan_id);
        Log::addLog($request->user_id, '', 'Delete : ' . $plan);

        Alert::success('Success', 'ลบข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function destroyfloor(Request $request)
    {

        $floor = Floor::where('id', $request->floor_id)->first();


        // if ($floor->image) {
        //     Storage::delete(str_replace(config('app.url') . '/storage/', '', $floor->image));
        // }

        if ($floor->image) {
            Storage::delete('floor/' . $floor->image);
        }


        $floor->delete($request->floor_id);
        Log::addLog($request->user_id, '', 'Delete : ' . $floor);

        Alert::success('Success', 'ลบข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function destroyFilePrice($id, $user_id)
    {
        $files = File_Price::where('id', $id)->first();

        if ($files->file) {
            Storage::delete('price/' . $files->file);
        }

        Log::addLog($user_id, '', 'Delete : ' . $files);

        $files->delete($id);

        return response()->json([
            'message' => 'ลบข้อมูลสำเร็จ',
            'id' => $files->project_id
        ], 201);

        return redirect()->back();
    }

}
