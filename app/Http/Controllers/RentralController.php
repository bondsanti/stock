<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Role_user;
use App\Models\User;
use App\Models\Rent;
use App\Models\Status_Rent;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Illuminate\Http\Request;

class RentralController extends Controller
{

    // public function index()
    // {
    //     $dataLoginUser = Session::get('loginId');
    //     //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
    //     $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
    //     $projects=[];
    //     // $projects = DB::connection('mysql_report')
    //     // ->table('project')
    //     // ->where('rent', 1)
    //     // ->orderBy('Project_Name', 'asc')
    //     // ->get();

    //     // $status = DB::connection('mysql_report')
    //     // ->table('rental_room')
    //     // ->select(DB::raw('DISTINCT status_room as name'))
    //     // ->whereRaw('IFNULL(status_room, "") NOT IN (?, ?)', ['', 'คืนห้อง'])
    //     // ->orderBy('status_room', 'ASC')
    //     // ->get();

    //     // $status = DB::connection('mysql_report')->table('rental_room')
    //     // ->select(DB::raw('
    //     //     CASE
    //     //         WHEN COALESCE(Status_room, "") = "" THEN "ห้องใหม่"
    //     //         WHEN Status_room = "จอง" THEN "จอง"
    //     //         WHEN Status_room = "พร้อมอยู่" THEN "พร้อมอยู่"
    //     //         WHEN Status_room IN ("รอคลีน", "รอตรวจ", "รอเฟอร์", "ไม่พร้อมอยู่") THEN "ไม่พร้อมอยู่"
    //     //         WHEN Status_room IN ("สวัสดิการ", "ห้องออฟฟิต", "เช่าอยู่", "อยู่แล้ว") THEN "อยู่แล้ว"
    //     //         WHEN Status_room = "คืนห้อง" THEN "คืนห้อง"
    //     //     END AS name
    //     // '))
    //     // ->groupBy('name')
    //     // ->orderBy('name', 'ASC')
    //     // ->get();


    //     $status = DB::connection('mysql_report')->table('rental_room')
    //     ->select(DB::raw('
    //         CASE
    //             WHEN COALESCE(Status_room, "") = "" THEN "ห้องใหม่"
    //             WHEN Status_room = "จอง" THEN "จอง"
    //             WHEN Status_room = "พร้อมอยู่" THEN "พร้อมอยู่"
    //             WHEN Status_room IN ("รอคลีน", "รอตรวจ", "รอเฟอร์", "ไม่พร้อมอยู่") THEN "ไม่พร้อมอยู่"
    //             WHEN Status_room IN ("สวัสดิการ", "ห้องออฟฟิต", "เช่าอยู่", "อยู่แล้ว") THEN "อยู่แล้ว"
    //         END AS name
    //     '))
    //     ->whereRaw('IFNULL(status_room, "") NOT IN (?, ?)', ['', 'คืนห้อง'])
    //     ->groupBy('name')
    //     ->orderBy('name', 'ASC')
    //     ->get();



    //     return view('rental.index',compact(
    //         'dataLoginUser',
    //         'isRole',
    //         'projects',
    //         'status'));
    // }

    public function index()
    {

        $dataLoginUser = Session::get('loginId');
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();

        try {

            $url = env('API_RENTAL') . '/project-rent';
            $url2 = env('API_RENTAL') . '/status-rent';
            $tokenapi = env('API_TOKEN_AUTH');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenapi
            ])->get($url);
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenapi
            ])->get($url2);


            //dd($response);
            if ($response->successful() && $response2->successful()) {

                $projects = $response->json()['data'];
                $status = $response2->json()['data'];

                return view('rental.index', compact(
                    'dataLoginUser',
                    'isRole',
                    'projects',
                    'status'
                ));
            } else {

                Alert::error('Error', 'ดึงข้อมูล API ไม่สำเร็จ');
                return back();
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับ API ภายนอก');
            return back();
        }
    }

    // public function search(Request $request)
    // {
    //     //dd($request->all());
    //     // $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
    //     // $isRole = Role_user::where('user_id', Session::get('loginId'))->first();

    //     $dataLoginUser = Session::get('loginId');
    //     $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();

    //     $projects = DB::connection('mysql_report')
    //         ->table('project')
    //         ->where('rent', 1)
    //         ->orderBy('Project_Name', 'asc')
    //         ->get();



    //     $status = DB::connection('mysql_report')->table('rental_room')
    //         ->select(DB::raw('
    //     CASE
    //         WHEN COALESCE(Status_room, "") = "" THEN "ห้องใหม่"
    //         WHEN Status_room = "จอง" THEN "จอง"
    //         WHEN Status_room = "พร้อมอยู่" THEN "พร้อมอยู่"
    //         WHEN Status_room IN ("รอคลีน", "รอตรวจ", "รอเฟอร์", "ไม่พร้อมอยู่") THEN "ไม่พร้อมอยู่"
    //         WHEN Status_room IN ("สวัสดิการ", "ห้องออฟฟิต", "เช่าอยู่", "อยู่แล้ว") THEN "อยู่แล้ว"
    //     END AS name
    //    '))
    //         ->whereRaw('IFNULL(status_room, "") NOT IN (?, ?)', ['', 'คืนห้อง'])
    //         ->groupBy('name')
    //         ->orderBy('name', 'ASC')
    //         ->get();




    //     $rents = Rent::select(
    //         'project.Project_Name',
    //         'rental_room.id',
    //         'rental_room.pid',
    //         'rental_room.Create_Date',
    //         'rental_room.HomeNo',
    //         'rental_room.RoomNo',
    //         'rental_room.RoomType',
    //         'rental_room.rental_status',
    //         'rental_room.Size',
    //         'rental_room.Owner',
    //         'rental_room.Status_Room',
    //         'rental_room.Phone',
    //         'rental_room.price',
    //         'rental_room.Trans_Status',
    //         'rental_room.Guarantee_Startdate',
    //         'rental_room.Guarantee_Enddate',
    //         'rental_room.date_firstrend',
    //         'rental_room.date_endrend',
    //         'rental_customer.Contract_Status',
    //         'rental_customer.Contract_Startdate',
    //         'rental_customer.Contract_Enddate',
    //         'rental_customer.Cus_Name'

    //     )
    //         ->join('project', 'rental_room.pid', '=', 'project.pid')
    //         ->leftJoin(DB::raw('(SELECT * FROM rental_customer WHERE Contract_Status = "เช่าอยู่"
    //      OR Contract_Status IS NULL OR Contract_Status = "") AS rental_customer'), function ($join) {
    //             $join->on('rental_room.pid', '=', 'rental_customer.pid')
    //                 ->on('rental_room.RoomNo', '=', 'rental_customer.RoomNo')
    //                 ->on('rental_room.id', '=', 'rental_customer.rid');
    //         })
    //         ->whereNotIn('rental_room.Status_room', ['คืนห้อง'])
    //         ->where(function ($query) {
    //             $query->where('rental_room.Trans_Status', '=', '')
    //                 ->orWhereNull('rental_room.Trans_Status');
    //         });


    //     if ($request->pid != 'all') {
    //         $rents->where('rental_room.pid', $request->pid);
    //     }

    //     if ($request->Owner) {
    //         $rents->where('rental_room.Owner', 'LIKE', '%' . $request->Owner . '%');
    //     }

    //     if ($request->RoomNo) {
    //         $rents->where('rental_room.RoomNo', 'LIKE', '%' . $request->RoomNo . '%');
    //     }

    //     if ($request->HomeNo) {
    //         $rents->where('rental_room.HomeNo', 'LIKE', '%' . $request->HomeNo . '%');
    //     }

    //     if ($request->Phone) {
    //         $rents->where('rental_room.Phone', 'LIKE', '%' . $request->Phone . '%');
    //     }

    //     if ($request->typerent != 'all') {
    //         $rents->where('rental_room.rental_status', $request->typerent);
    //     }

    //     if ($request->status != 'all') {
    //         if ($request->status == "ไม่พร้อมอยู่") {
    //             $rents->whereIn('rental_room.Status_Room', ['ไม่พร้อมอยู่', 'รอคลีน', 'รอตรวจ', 'รอเฟอร์']);
    //         } elseif ($request->status == "อยู่แล้ว") {
    //             $rents->whereIn('rental_room.Status_Room', ['สวัสดิการ', 'ห้องออฟฟิต', 'เช่าอยู่', 'อยู่แล้ว']);
    //         } else {
    //             $rents->where('rental_room.Status_Room', $request->status);
    //         }
    //     }

    //     if ($request->startdate  && $request->enddate) {
    //         $rents->whereBetween('rental_room.Create_Date', [$request->startdate, $request->enddate]);
    //     }

    //     $rentsCount = $rents->count();

    //     $rents = $rents
    //         ->orderBy('Project_Name', 'asc')
    //         ->get();


    //     $formInputs = $request->all();



    //     return view('rental.search', compact(
    //         'rents',
    //         'dataLoginUser',
    //         'isRole',
    //         'projects',
    //         'status',
    //         'formInputs',
    //         'rentsCount'
    //     ));
    // }

    public function search(Request $request)
    {
        $dataLoginUser = Session::get('loginId');
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();

        $formInputs = $request->all();
        try {

            $url = env('API_RENTAL') . '/project-rent';
            $url2 = env('API_RENTAL') . '/status-rent';
            $url3 = env('API_RENTAL') . '/search-rental';
            $tokenapi = env('API_TOKEN_AUTH');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenapi
            ])->get($url);
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenapi
            ])->get($url2);
            $response3 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenapi
            ])->post($url3, $formInputs);


            //dd($response);
            if ($response->successful() && $response2->successful()&& $response3->successful()) {

                $projects = $response->json()['data'];
                $status = $response2->json()['data'];
                $rents = $response3->json()['data'];
                $rentsCount = is_array($rents) ? count($rents) : 0;
                //dd($rents);
                return view('rental.search', compact(
                    'rents',
                    'dataLoginUser',
                    'isRole',
                    'projects',
                    'status',
                    'formInputs',
                    'rentsCount'
                ));
            } else {

                Alert::error('Error', 'ดึงข้อมูล API ไม่สำเร็จ');
                return back();
            }
        } catch (\Exception $e) {

            Alert::error('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อกับ API ภายนอก');
            return back();
        }
    }
}
