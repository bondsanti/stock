<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Role_user;
use App\Models\Log;
use DB;
use Illuminate\Support\Facades\Http;
use Session;

class CustomAuthController extends Controller
{
    public function login()
    {
      return view('auth.login');
        //return redirect('https://vbnext.vbeyond.co.th/main');
        //return redirect('http://127.0.0.1:8000/main');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'password' => 'required'
        ], [
            'code.required' => 'ป้อนรหัสพนักงาน',
            'password.required' => 'ป้อนรหัสผ่าน'
        ]);

        $code = $request->code;
        $password = $request->password;

        try {

            $url = env('API_URL') . '/getAuth/' . $code;
            $token = env('API_TOKEN_AUTH');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$token
            ])->get($url);
                //dd($response);

                $url_log = env('API_URL') . '/create/login/log/' . $code . ',stock';
                //insert loglogin
                $response_log = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token
                ])->post($url_log);
                if ($response_log->successful()) {
                }

            if ($response->successful()) {
                $userData = $response->json()['data'];

                if (Hash::check($password, $userData['password'])) {
                    $request->session()->put('loginId',$userData);
                    Alert::success('เข้าสู่ระบบสำเร็จ');
                    return redirect('/');
                } else {
                    Alert::warning('รหัสผ่านไม่ถูกต้อง', 'กรุณากรอกข้อมูลใหม่อีกครั้ง');
                    return back();
                }
            } else {

            Alert::warning('ไม่พบผู้ใช้งาน', 'กรุณากรอกข้อมูลใหม่อีกครั้ง');
            return back();

            }
        } catch (\Exception $e) {

            Alert::error('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับ API ภายนอก');
            return back();
        }
    }

    public function logoutUser(Request $request)
    {

        if ($request->session()->has('loginId')) {

           // Log::addLog($request->session()->get('loginId.id'), '', 'Logout');

            $request->session()->pull('loginId');
            Alert::success('ออกจากระบบเรียบร้อย', 'ไว้พบกันใหม่ :)');

            return redirect('login');
        }
    }

    public function AllowLoginConnect(Request $request,$code,$token)
    {
        //dd($token);
        try {

            $url = env('API_URL') . '/checktoken/out/' . $token;
            $tokenapi = env('API_TOKEN_AUTH');
           //dd($url);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$tokenapi
            ])->get($url);

            $url_log = env('API_URL') . '/create/login/log/' . $code . ',stock';
            //insert loglogin
            $response_log = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenapi
            ])->post($url_log);
            if ($response_log->successful()) {
            }
            //dd($response);
            if ($response->successful()) {
                $userData = $response->json()['data'];

                    $request->session()->put('loginId',$userData);
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

    public function changePassword()
    {
        return view('auth.password');
    }

    public function updatePassword(Request $request)
    {
        $user = User::where('id', Session::get('dataIsAuth')->id)->first();
        // dd(Session::get('dataIsAuth')->id);
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'regex:/[a-z]/', //one lowercase letter
                'regex:/[A-Z]/', //one uppercase letter
                'regex:/[0-9]/', //one least one digit
                'regex:/[@$!%*#?&]/', //one least one character
            ],

            'cfpassword' => ['required', 'same:password']

        ], [
            'password.required' => 'ป้อนรหัสผ่านใหม่',
            'cfpassword.same' => 'รหัสผ่านไม่ตรงกัน',
            'password.min' => 'รหัสผ่านต้องไม่ต่ำกว่า 8 ตัวอักษร',
            'password.regex' => 'รหัสผ่านอย่างน้อยต้องมี ตัวพิมพ์เล็ก 1 ตัว,ตัวพิมพ์ใหญ่ 1 ตัว,ตัวเลข 1 ตัว และอักษรพิเศษ 1 ตัว',
            'cfpassword.required' => 'ป้อนยืนยันรหัสผ่านใหม่',

        ]);

        if (Hash::check($request->password, $user->password)) {
            Alert::warning('รหัสผ่านซ้ำกับรหัสเดิม', 'กรุณากรอกข้อมูลใหม่อีกครั้ง');
            return back();
        } else {
            $user->old_password = $user->password;
            $user->password = Hash::make($request->password);
            $user->is_auth = "1";
            $user->save();

            $user->refresh();

            $request->session()->put('loginId', $user->id);

            DB::table('vbeyond_report.log_login')->insert([
                'username' => $user->code,
                'dates' => date('Y-m-d'),
                'timeStm' => date('Y-m-d H:i:s'),
                'page' => 'Stock'
            ]);

            Log::addLog($request->session()->get('loginId'), '', 'Login');


            Alert::success('เข้าสู่ระบบสำเร็จ!');
            return redirect('/');
        }


    }


}
