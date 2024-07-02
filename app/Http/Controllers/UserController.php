<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Role_user;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(Request $request)
    {

        // $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $dataLoginUser = Session::get('loginId');
        //dd($dataLoginUser);

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();

        $countUser = Role_user::where('active', 1)->count();
        $countUserSPAdmin = Role_user::where('active', 1)->where('role_type', 'SuperAdmin')->count();
        $countUserAdmin = Role_user::where('active', 1)->where('role_type', 'Admin')->count();
        $countUserStaff = Role_user::where('active', 1)->where('role_type', 'Staff')->count();
        $countUserSale = Role_user::where('active', 1)->where('role_type', 'Sale')->count();
        $countUsers = Role_user::where('active', 1)->where('role_type', 'User')->count();
        $countUserPartner = Role_user::where('active', 1)->where('role_type', 'Partner')->count();

        // $users = Role_user::with('role:id,code,name_th,active','position:id,name')->orderBy('id', 'desc')->get();

        $users = Role_user::orderBy('id', 'desc')->where('active', 1)->get();
        $userIds = $users->pluck('user_id')->toArray(); // ดึง user_id เป็น array


        $userIdsString = implode(',', $userIds);

        $apiUrl = env('API_URL');
        $tokenapi = env('API_TOKEN_AUTH');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $tokenapi
        ])->get($apiUrl . '/get-role/users/' . $userIdsString);

        if ($response->successful()) {
            $apiData = collect($response->json()['data']);

            // วนลูปเพิ่มข้อมูลที่ได้จาก API ใน users collection
            foreach ($users as $user) {
                $userApiData = $apiData->firstWhere('user_id', $user->user_id);
                if ($userApiData) {
                    $user->api_data = [
                        'code' => $userApiData['code'],
                        'email' => $userApiData['email']
                    ];
                } else {
                    $user->api_data = null;
                }
            }
        } else {
            foreach ($users as $user) {
                $user->api_data = null;
            }
        }

        //return response()->json($users);

        if ($isRole->role_type == "SuperAdmin") {
            return view('users.index', compact(
                'dataLoginUser',
                'users',
                'countUser',
                'countUserSPAdmin',
                'countUserAdmin',
                'countUserStaff',
                'countUserSale',
                'countUserPartner',
                'countUsers',
                'isRole'
            ));
        } else {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ], [
            'code.required' => 'กรอก Code',
        ]);

        if ($validator->passes()) {

            $userFound = User::where('code', $request->code)->where('active', 1)->count();
            if ($userFound === 0) {
                return response()->json([
                    'message' => 'รหัสผิด / ไม่พบผู้ใช้งาน / ลาออก'
                ], 400);
            }
            $users = User::where('code', $request->code)->where('active', 1)->first();
            $exitUsers = Role_user::where('user_id', $users->id)->first();
            if ($exitUsers) {
                return response()->json([
                    'message' => 'มีผู้ใช้ท่านนี้อยู่ในระบบแล้ว'
                ], 400);
            }


            if ($request->role_type == "User") {
                if (empty($request->dept)) {
                    $dept = "";
                } else {
                    $dept = $request->dept;
                }
            } elseif ($request->role_type == "Partner") {
                $dept = $request->dept2;
            } else {
                $dept = "";
            }

            $roleUser = new Role_user();
            $roleUser->user_id = $users->id;
            $roleUser->role_id = $users->position_id;
            $roleUser->role_type = $request->role_type;

            $roleUser->dept = $dept;


            $roleUser->active = 1;
            $roleUser->save();

            if ($request->hasFile('logo')) {

                $image = $request->file('logo');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/logo-partner/'), $imageName);
                $imageUrl = asset('uploads/logo-partner/' . $imageName);
                $roleUser->logo = $imageUrl;
                $roleUser->save();
            }


            // $roleUser->refresh();

            Log::addLog($request->user_id, '', 'Create RoleUser : ' . $roleUser);

            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ'
            ], 201);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id, $user_id)
    {


        $roleUser = Role_user::where('id', $id)->first();

        if ($roleUser->role_type == "Partner") {
            $user = User::find($roleUser->user_id);
            if ($user) {
                $user->active = 0;
                $user->is_auth = 0;
                $user->resign_date = date('Y-m-d');
                $user->save();
            }
        }

        $roleUser_old = $roleUser->toArray();

        if (file_exists($roleUser->logo)) {
            unlink($roleUser->logo);
        }

        Log::addLog($user_id, json_encode($roleUser_old), 'Delete RoleUser : ' . $roleUser);
        $roleUser->delete($id);

        return response()->json([
            'message' => 'ลบข้อมูลสำเร็จ'
        ], 201);
    }

    public function edit($id)
    {

        // $users = Role_user::with('role:id,code,name_th,active', 'position:id,name')->where('id', $id)->first();
        $users = Role_user::where('id', $id)->first();

        $apiUrl = env('API_URL');
        $tokenapi = env('API_TOKEN_AUTH');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $tokenapi
        ])->get($apiUrl . '/get-role/users/' . $users->user_id);

        if ($response->successful()) {
            $apiData = collect($response->json()['data']);

            // วนลูปเพิ่มข้อมูลที่ได้จาก API ใน users collection

            $userApiData = $apiData->firstWhere('user_id', $users->user_id);
            if ($userApiData) {
                $users->api_data = [
                    'code' => $userApiData['code'],
                    'email' => $userApiData['email']
                ];
            } else {
                $users->api_data = null;
            }
        } else {

            $users->api_data = null;
        }
        // dd($users);
        return response()->json($users, 200);
    }

    public function update(Request $request, $id)
    {

        $roleUser = Role_user::where('id', $id)->first();
        $roleUser_old = $roleUser->toArray();


        if ($request->role_type_edit == "User") {
            if (empty($request->dept_edit)) {
                $dept = "";
            } else {
                $dept = $request->dept_edit;
            }
        } elseif ($request->role_type_edit == "Partner") {
            $dept = $request->dept_edit2;
        } else {
            $dept = "";
        }





        $roleUser->role_type = $request->role_type_edit;

        $roleUser->dept = $dept;


        $roleUser->save();



        if ($request->hasFile('logo_edit')) {

            if (file_exists($roleUser->logo)) {
                unlink($roleUser->logo);
            }

            $image = $request->file('logo_edit');

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/logo-partner/'), $imageName);
            $imageUrl = asset('uploads/logo-partner/' . $imageName);
            $roleUser->logo = $imageUrl;
            $roleUser->save();
        }

        Log::addLog($request->user_id, json_encode($roleUser_old), 'Update RoleUser : ' . $roleUser);

        return response()->json([
            'message' => 'อัพเดทข้อมูลสำเร็จ'
        ], 201);
    }

    public function createPartner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'name_th' => 'required',
            'deptRole' => 'required',
        ], [
            'username.required' => 'กรอก Username',
            'name_th.required' => 'กรอก ชื่อ-สกุล',
            'deptRole.required' => 'กรอก Partner',
        ]);

        if ($validator->passes()) {

            $exitUsers = User::where('code', $request->username)->first();
            //$exitUsers = Role_user::where('user_id',$users->id)->first();
            if ($exitUsers) {
                return response()->json([
                    'message' => 'มีผู้ใช้ท่านนี้อยู่ในระบบแล้ว'
                ], 400);
            }

            $createPartner = new User();
            $createPartner->code = $request->username;
            $createPartner->name_th = $request->name_th;
            $createPartner->sup_team = $request->username;
            $createPartner->is_auth = 0;
            $createPartner->department_id = 14;
            $createPartner->position_id = 72;
            $createPartner->company_id = 1;
            $createPartner->password = bcrypt('123456');
            $createPartner->active = 1;
            $createPartner->save();

            $roleUser = new Role_user();
            $roleUser->user_id = $createPartner->id;
            $roleUser->role_id = $createPartner->position_id;
            $roleUser->role_type = 'Partner';
            $roleUser->dept = $request->deptRole;
            $roleUser->active = 1;
            $roleUser->save();

            if ($request->hasFile('logoPartner')) {

                $image = $request->file('logoPartner');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/logo-partner/'), $imageName);
                $imageUrl = asset('uploads/logo-partner/' . $imageName);
                $roleUser->logo = $imageUrl;
                $roleUser->save();
            }


            // $roleUser->refresh();

            Log::addLog($request->user_id, '', 'Create PartnerUser : ' . $createPartner);
            Log::addLog($request->user_id, '', 'Create RoleUser : ' . $roleUser);

            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ'
            ], 201);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    //CreateUser from AgentSystem
    public function createUserByAgentSystem(Request $request, $id, $role_id, $code)
    {

        $exitUsers = Role_user::where('user_id', $id)->first();

        if ($exitUsers) {

            $request->session()->put('loginId', $exitUsers->user_id);

            // DB::table('vbeyond_report.log_login')->insert([
            //     'username' => $exitUsers->user_id,
            //     'dates' => date('Y-m-d'),
            //     'timeStm' => date('Y-m-d H:i:s'),
            //     'page' => 'Stock'
            // ]);
            try {

                $url = env('API_URL') . '/getAuth/' . $code;
                $token = env('API_TOKEN_AUTH');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token
                ])->get($url);
                //dd($response);

                if ($response->successful()) {
                    $userData = $response->json()['data'];

                        $request->session()->put('loginId', $userData);
                        Alert::success('เข้าสู่ระบบสำเร็จ');
                        return redirect('/');

                } else {

                    Alert::warning('ไม่พบผู้ใช้งาน', 'กรุณากรอกข้อมูลใหม่อีกครั้ง');
                    return back();
                }
            } catch (\Exception $e) {

                Alert::error('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับ API ภายนอก');
                return back();
            }


            //Log::addLog($request->session()->get('loginId'), '', 'Login');

            Alert::success('เข้าสู่ระบบสำเร็จ');
            return redirect('/');
        } else {

            $roleUser = new Role_user();
            $roleUser->user_id = $id;
            $roleUser->role_id = $role_id;
            $roleUser->role_type = 'Sale';
            $roleUser->active = 1;
            $roleUser->save();

            //Log::addLog($roleUser->user_id, '', 'Create RoleUser : ' . $roleUser);


            try {

                $url = env('API_URL') . '/getAuth/' . $code;
                $token = env('API_TOKEN_AUTH');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token
                ])->get($url);
                //dd($response);

                if ($response->successful()) {
                    $userData = $response->json()['data'];

                        $request->session()->put('loginId', $userData);
                        Alert::success('เข้าสู่ระบบสำเร็จ');
                        return redirect('/');

                } else {

                    Alert::warning('ไม่พบผู้ใช้งาน', 'กรุณากรอกข้อมูลใหม่อีกครั้ง');
                    return back();
                }
            } catch (\Exception $e) {

                Alert::error('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับ API ภายนอก');
                return back();
            }
        }
    }
}
