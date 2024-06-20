<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Role_user;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index(Request $request)
    {

        // $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();

        $dataLoginUser = Session::get('loginId');
        // dd($dataLoginUser['apiData']['data']['name_th']);
        
        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);

        //ทั้งหมด
        $all = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
            ->select([
                'projects.name as project',
                'projects.active',
                'projects.high_rise',
                'projects.low_rise',
                'rooms.status_id'
            ])
            ->where('projects.active', 1)
            ->count();

        //ออกใบเสนอราคา
        if ($isRole->role_type == "Partner") {
            $bid = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
            ->join('bookings', 'rooms.id', '=', 'bookings.rooms_id')
            ->select(
                'projects.name as project',
                'projects.active',
                'projects.high_rise',
                'projects.low_rise',
                // DB::raw('LOWER(rooms.agent) as agent'),
                // 'rooms.status_id',
                DB::raw('LOWER(bookings.team) as team')
            )
            ->where('projects.active', 1)
            ->where('rooms.status_id', 8)
            ->when($isRole->dept, function ($query, $dept) {
                return $query->where(function ($query) use ($dept) {
                    $query->whereRaw('LOWER(bookings.team) LIKE ?', ['%' . strtolower($dept) . '%']);
                });
            })
            ->count();
            }else{
                $bid = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
                ->select([
                    'projects.name as project',
                    'projects.active',
                    'projects.high_rise',
                    'projects.low_rise',
                    'rooms.status_id'
                ])
                ->where('projects.active', 1)
                ->where('status_id', 8)
                ->count();
            }

        //ว่าง
        $empty = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
            ->select([
                'projects.name as project',
                'projects.active',
                'projects.high_rise',
                'projects.low_rise',
                'rooms.status_id'
            ])
            ->where('projects.active', 1)
            ->where('rooms.status_id', 1)
            ->count();

        //จองแล้ว
        if ($isRole->role_type == "Partner") {


            $bookings = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
            ->join('bookings', 'rooms.id', '=', 'bookings.rooms_id')
            ->select(
                'projects.name as project',
                'projects.active',
                'projects.high_rise',
                'projects.low_rise',
                // DB::raw('LOWER(rooms.agent) as agent'),
                // 'rooms.status_id',
                DB::raw('LOWER(bookings.team) as team')
            )
            ->where('projects.active', 1)
            ->where('rooms.status_id', 2)
            ->when($isRole->dept, function ($query, $dept) {
                return $query->where(function ($query) use ($dept) {
                    $query->whereRaw('LOWER(bookings.team) LIKE ?', ['%' . strtolower($dept) . '%']);
                });
            })
            ->count();

        } else {

            $bookings = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
                ->select([
                    'projects.name as project',
                    'projects.active',
                    'projects.high_rise',
                    'projects.low_rise',
                    'rooms.status_id'
                ])
                ->where('projects.active', 1)
                ->where('rooms.status_id', 2)
                ->count();
        }

        //ทำสัญญาแล้ว
        if ($isRole->role_type == "Partner") {
            $contract = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
            ->join('bookings', 'rooms.id', '=', 'bookings.rooms_id')
            ->select(
                'projects.name as project',
                'projects.active',
                'projects.high_rise',
                'projects.low_rise',
                // DB::raw('LOWER(rooms.agent) as agent'),
                // 'rooms.status_id',
                DB::raw('LOWER(bookings.team) as team')
            )
            ->where('projects.active', 1)
            ->where('rooms.status_id', 3)
            ->when($isRole->dept, function ($query, $dept) {
                return $query->where(function ($query) use ($dept) {
                    $query->whereRaw('LOWER(bookings.team) LIKE ?', ['%' . strtolower($dept) . '%']);
                });
            })
            ->count();
        } else {
            $contract = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
                ->select([
                    'projects.name as project',
                    'projects.active',
                    'projects.high_rise',
                    'projects.low_rise',
                    'rooms.status_id'
                ])
                ->where('projects.active', 1)
                ->where('rooms.status_id', 3)
                ->count();
        }

        //โอนแล้ว

        if ($isRole->role_type == "Partner") {
            $mortgage = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
            ->join('bookings', 'rooms.id', '=', 'bookings.rooms_id')
            ->select(
                'projects.name as project',
                'projects.active',
                'projects.high_rise',
                'projects.low_rise',
                // DB::raw('LOWER(rooms.agent) as agent'),
                // 'rooms.status_id',
                DB::raw('LOWER(bookings.team) as team')
            )
            ->where('projects.active', 1)
            ->where('rooms.status_id', 4)
            ->when($isRole->dept, function ($query, $dept) {
                return $query->where(function ($query) use ($dept) {
                    $query->whereRaw('LOWER(bookings.team) LIKE ?', ['%' . strtolower($dept) . '%']);
                });
            })

            ->count();

            // To get the SQL query
            //$sql = $mortgage->toSql();

            // To get the bindings
           // $bindings = $mortgage->getBindings();

            // Output the SQL query and bindings
            //dd($sql, $bindings);

        } else {

            $mortgage = Room::join('projects', 'rooms.project_id', '=', 'projects.id')
                ->select([
                    'projects.name as project',
                    'projects.active',
                    'projects.high_rise',
                    'projects.low_rise',
                    'rooms.status_id'
                ])
                // ->where(function ($query) use ($dataLoginUser) {
                //     $query->where('projects.high_rise', $dataLoginUser->high_rise)
                //         ->orWhere('projects.low_rise', $dataLoginUser->low_rise);
                // })
            ->where('projects.active', 1)
            ->where('rooms.status_id', 4)
            ->count();
        }

        // ======================== new ======================== ///

        $dataBookings = Booking::leftJoin('status_rooms', 'status_rooms.id', 'bookings.booking_status')
            ->leftJoin('rooms', 'rooms.id', 'bookings.rooms_id')
            ->leftJoin('projects', 'projects.id', 'rooms.project_id')
            ->leftJoin('plans', 'plans.id', 'rooms.plan_id')
            ->select(
                'bookings.*',
                'status_rooms.name as status',
                'rooms.address',
                'rooms.room_address',
                'rooms.area',
                'projects.name as project',
                'projects.active',
                'plans.name as type'
            )
            ->where('projects.active', 1)->whereIn('bookings.booking_status', [2, 3, 8])
            ->orderByDesc('bookings.booking_date')
            ->orderByDesc('bookings.booking_contract')
            ->get();


        $expList = [];
        $totalExp = 0;

        foreach ($dataBookings as $booking) {
            $currentDate = Carbon::now();
            $daysPassed = 0;
            $daysOverdue = 0;

            switch ($booking->booking_status) {
                case 8: //สถานะใบเสนอราคา
                    $dayExp = 14;
                    $date = Carbon::parse($booking->bid_date);
                    $daysPassed = $date->diffInDays($currentDate);
                    $daysOverdue = $daysPassed > $dayExp ? $daysPassed - $dayExp : 0;
                    break;

                case 2: //สถานะจองแล้ว
                    $dayExp = 7;
                    $date = Carbon::parse($booking->booking_date);
                    $daysPassed = $date->diffInDays($currentDate);
                    $daysOverdue = $daysPassed > $dayExp ? $daysPassed - $dayExp : 0;
                    break;

                case 3: //สถานะทำสัญญา
                    $dayExp = 30;
                    $date = Carbon::parse($booking->booking_contract);
                    $daysPassed = $date->diffInDays($currentDate);
                    $daysOverdue = $daysPassed > $dayExp ? $daysPassed - $dayExp : 0;
                    break;
            }

            $booking->daysPassed = $daysPassed;
            $booking->daysOverdue = $daysOverdue;

            if ($booking->daysOverdue <= 0) {
                continue; // Skip this booking if daysOverdue is not greater than 0
            }
            $totalExp++;
            // Group bookings by project and then by rooms_id
            if (!isset($expList[$booking->project])) {
                $expList[$booking->project] = [];
            }

            if (!isset($expList[$booking->project][$booking->rooms_id])) {
                $expList[$booking->project][$booking->rooms_id] = [];
            }

            $expList[$booking->project][$booking->rooms_id][] = $booking;
        }

        //return response()->json($totalExp);

        // ======================== close new ======================== ///



        // ======================== old ========================   ///
        // $expire = Room::select(
        //     'rooms.id',
        //     'projects.name as project',
        //     'plans.name as plan',
        //     'rooms.room_address',
        //     'rooms.address',
        //     'rooms.building',
        //     'rooms.area',
        //     'rooms.owner',
        //     'rooms.booking_date',
        //     'rooms.contract_date',
        //     'rooms.remark',
        //     'rooms.sladay',
        //     'rooms.agent',
        //     'status_rooms.name as status',
        //     'projects.high_rise',
        //     'projects.low_rise'
        // )
        // ->join('status_rooms', 'rooms.status_id', '=', 'status_rooms.id')
        // ->join('projects', 'rooms.project_id', '=', 'projects.id')
        // ->join('plans','rooms.plan_id','=','plans.id')
        // ->where('rooms.sladay', '>', 0)
        // ->whereIn('rooms.status_id', [2, 3])
        // ->orderByDesc('rooms.booking_date')
        // ->orderByDesc('rooms.contract_date')
        // ->get();

        // $expCount = Room::where('rooms.sladay', '>', 0)
        // ->whereIn('rooms.status_id', [2, 3])
        // ->count();

        //dd($expire);
        // ======================== close old ======================== ///



        if ($isRole->role_type == "Partner") {

            return view('main.partner', compact(
                'dataLoginUser',
                'all',
                'bid',
                'empty',
                'bookings',
                'contract',
                'mortgage',
                'expList',
                'totalExp',
                'isRole',
            ));
        }elseif(empty($isRole->role_type)){
            return view('main.noassign', compact(
                'dataLoginUser',
                'all',
                'bid',
                'empty',
                'bookings',
                'contract',
                'mortgage',
                'expList',
                'totalExp',
                'isRole',
            ));
        } else {

            //return response()->json($expList, 200);

            return view('main.index', compact(
                'dataLoginUser',
                'all',
                'bid',
                'empty',
                'bookings',
                'contract',
                'mortgage',
                'expList',
                'totalExp',
                'isRole',
            ));
        }
    }
}
