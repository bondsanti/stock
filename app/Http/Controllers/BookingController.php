<?php

namespace App\Http\Controllers;

use Session;
use DB;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Log;
use App\Models\Room;
use App\Models\Role_user;
use App\Models\Status_Room;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;



class BookingController extends Controller
{

    public function getRoom($id)
    {
        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $dataLoginUser = Session::get('loginId');
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        $rooms = Room::with(['project', 'plan', 'user', 'status'])->where('id', $id)->first();
        $status = Status_Room::orderBy('id', 'desc')->whereIn('name', ['จองแล้ว', 'ทำสัญญา', 'โอน', 'ออกใบเสนอราคา'])->get();
        $sutatusBid =  Status_Room::orderBy('id', 'desc')->whereIn('name', ['จองแล้ว', 'ออกใบเสนอราคา', 'ทำสัญญา'])->get();
        $teams = Team::orderBy('team_name', 'asc')->get();
        // $bookings = Booking::leftJoin('status_rooms', 'status_rooms.id', '=', 'bookings.booking_status')
        // ->select('bookings.*', 'status_rooms.name')
        // ->where('rooms_id',$id)->get();

        $bookings = Booking::leftJoin('status_rooms', 'status_rooms.id', 'bookings.booking_status')
            ->select('bookings.*', 'status_rooms.name as status')
           // ->where('rooms_id', $id)->where('booking_status', '<=', 9)
            ->where('rooms_id', $id)
            ->get();
        $totalBooking = $bookings->count();
        // นับจำนวนการจองทั้งหมดที่มีสถานะ "ยกเลิก"
        $cancelledBookings = $bookings->where('status', 'ยกเลิก')->count();
        $mBookings = $bookings->where('status', 'โอน')->count();
        // หาจำนวนการจองทั้งหมดที่ไม่ใช่สถานะ "ยกเลิก"
        $totalValidBookings = $totalBooking - $cancelledBookings;

        foreach ($bookings as &$booking) {
            if ($booking->booking_status == 8) {
                $dayExp = 14;
                $bidDate = Carbon::parse($booking->bid_date);
                $currentDate = Carbon::now();
                $daysPassed = $bidDate->diffInDays($currentDate);

                // ตรวจสอบว่า daysPassed มากกว่า $dayExp หรือไม่
                if ($daysPassed > $dayExp) {
                    $booking->daysOverdue = $daysPassed - $dayExp;  // คำนวณวันที่เกินกำหนด
                } else {
                    $booking->daysOverdue = 0;  // ถ้าไม่เกินกำหนดให้กำหนดเป็น null
                }

                $booking->daysPassed = $daysPassed;
            } elseif ($booking->booking_status == 2) {
                $dayExp = 7;
                $bookingDate = Carbon::parse($booking->booking_date);
                $currentDate = Carbon::now();
                $daysPassed = $bookingDate->diffInDays($currentDate);

                // ตรวจสอบว่า daysPassed มากกว่า $dayExp หรือไม่
                if ($daysPassed > $dayExp) {
                    $booking->daysOverdue = $daysPassed - $dayExp;  // คำนวณวันที่เกินกำหนด
                } else {
                    $booking->daysOverdue = 0;  // ถ้าไม่เกินกำหนดให้กำหนดเป็น null
                }

                $booking->daysPassed = $daysPassed;
            } elseif ($booking->booking_status == 3) {
                $dayExp = 30;
                $contractDate = Carbon::parse($booking->booking_contract);
                $currentDate = Carbon::now();
                $daysPassed = $contractDate->diffInDays($currentDate);

                // ตรวจสอบว่า daysPassed มากกว่า $dayExp หรือไม่
                if ($daysPassed > $dayExp) {
                    $booking->daysOverdue = $daysPassed - $dayExp;  // คำนวณวันที่เกินกำหนด
                } else {
                    $booking->daysOverdue = 0;  // ถ้าไม่เกินกำหนดให้กำหนดเป็น null
                }

                $booking->daysPassed = $daysPassed;
            } else {
                $booking->daysPassed = 0;
                $booking->daysOverdue = 0;
            }
        }

        return view('rooms.booking', compact(
            'dataLoginUser',
            'rooms',
            'status',
            'isRole',
            'bookings',
            'sutatusBid',
            'totalBooking',
            'totalValidBookings',
            'cancelledBookings',
            'mBookings',
            'teams'
        ));
    }

    public function bookingRoom(Request $request)
    {


        //เช็ควันที่เมื่อเลือกสถานะต่าง ๆ ให้ทำการ validate
        if ($request->status_id == "8") { //เสนอราคา
            $validator = Validator::make($request->all(), [
                'customer_fname' => 'required',
                'customer_sname' => 'required',
                'customer_tel' => 'required',
                'agent' => 'required',
                'agent_phone' => 'required',
                'bid_date' => 'required',
            ], [
                'customer_fname.required' => 'กรอก ชื่อ ',
                'customer_sname.required' => 'กรอก นามสกุล',
                'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
                'agent.required' => 'กรอก ทีม',
                'agent_phone.required' => 'กรอก เบอร์สายงาน',
                'bid_date.required' => 'กรอก วันที่',
            ]);
        } elseif ($request->status_id == "2") { //จองแล้ว
            $validator = Validator::make($request->all(), [
                'customer_fname' => 'required',
                'customer_sname' => 'required',
                'customer_tel' => 'required',
                'agent' => 'required',
                'agent_phone' => 'required',
                'booking_date' => 'required',
            ], [
                'customer_fname.required' => 'กรอก ชื่อ ',
                'customer_sname.required' => 'กรอก นามสกุล',
                'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
                'agent.required' => 'กรอก ทีม',
                'agent_phone.required' => 'กรอก เบอร์สายงาน',
                'booking_date.required' => 'กรอก วันที่',
            ]);
        } elseif ($request->status_id == "3") { //ทำสัญญา
            $validator = Validator::make($request->all(), [
                'customer_fname' => 'required',
                'customer_sname' => 'required',
                'customer_tel' => 'required',
                'agent' => 'required',
                'agent_phone' => 'required',
                'booking_contract' => 'required',
            ], [
                'customer_fname.required' => 'กรอก ชื่อ ',
                'customer_sname.required' => 'กรอก นามสกุล',
                'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
                'agent.required' => 'กรอก ทีม',
                'agent_phone.required' => 'กรอก เบอร์สายงาน',
                'booking_contract.required' => 'กรอก วันที่',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'customer_fname' => 'required',
                'customer_sname' => 'required',
                'customer_tel' => 'required',
                'agent' => 'required',
                'agent_phone' => 'required',
                'mortgage_date' => 'required',
            ], [
                'customer_fname.required' => 'กรอก ชื่อ ',
                'customer_sname.required' => 'กรอก นามสกุล',
                'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
                'agent.required' => 'กรอก ทีม',
                'agent_phone.required' => 'กรอก เบอร์สายงาน',
                'mortgage_date.required' => 'กรอก วันที่',
            ]);
        }


        if ($validator->passes()) {

            // $existingBookings = Booking::where('rooms_id', $request->roomId)->where('booking_status', ">", 0)->count();

            // if ($existingBookings > 3) {
            //     //dd($request->roomId);
            //     return response()->json([
            //         'message' => 'ไม่สามารถจองได้จำนวนลูกค้าเต็ม'
            //     ], 400);
            // }

            //ref status
            // id | name
            // 1 = ห้องว่าง
            // 2 = จองแล้ว
            // 3 = ทำสัญญา
            // 4 = โอน
            // 5 = คืนห้อง
            // 6 = ไม่พร้อมขาย
            // 8 = ออกใบเสนอราคา
            // 9 = ยกเลิก

            //ดึงรายการจองล่าสุด
            $latestBooking = Booking::where('rooms_id', $request->roomId)
                ->orderBy('id', 'desc')
                ->first();

                //dd($latestBooking);

            $rooms = Room::where('id', $request->roomId)->first();

            // ตรวจสอบว่ามีการจองห้องหรือไม่
            if (!$latestBooking) {

                $rooms->status_id = $request->status_id;
                $rooms->save();
                $rooms_old = $rooms->toArray();

                $bookings = new Booking();
                $bookings->rooms_id = $request->roomId;
                $bookings->booking_by = $request->user_id;
                $bookings->customer_fname = $request->customer_fname;
                $bookings->customer_sname = $request->customer_sname;
                $bookings->customer_tel = $request->customer_tel;
                $bookings->workfield = $request->workfield;
                $bookings->team = $request->agent;
                $bookings->team_tel = $request->agent_phone;
                $bookings->booking_status = $request->status_id;
                $bookings->bid_date = $request->bid_date;
                $bookings->booking_date = $request->booking_date;
                $bookings->booking_contract = $request->booking_contract;
                $bookings->mortgage_date = $request->mortgage_date;
                $bookings->remark = $request->remark;
                $bookings->save();
                $bookings->refresh();


                Log::addLog($request->user_id, json_encode($rooms_old), 'Update Status Room : ' . $rooms);
                Log::addLog($request->user_id, '', 'Create Booking Room : ' . $bookings);

                return response()->json([
                    'message' => 'เพิ่มข้อมูลสำเร็จ'
                ], 201);

                return redirect()->back();
            }

            // กำสถานะของลูกค้าเป็น Step
            $stepMapping = [
                '8' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4
            ];

            $currentCustomerStep = $stepMapping[$latestBooking->booking_status] ?? null;

            // ตรวจสอบสถานะปัจจุบันของห้อง
            $currentRoomStatus = $rooms->status_id;

            // if (!$currentCustomerStep || $stepMapping[$currentRoomStatus] >= $currentCustomerStep) {
            //     // ถ้าสถานะปัจจุบันของห้องมากกว่า หรือ เท่ากับ สถานะของลูกค้าที่จองล่าสุด
            //     // ไม่ทำการอัปเดทสถานะห้อง

                $rooms->status_id = $latestBooking->booking_status;
                $rooms->save();
                $rooms_old = $rooms->toArray();

                $bookings = new Booking();
                $bookings->rooms_id = $request->roomId;
                $bookings->booking_by = $request->user_id;
                $bookings->customer_fname = $request->customer_fname;
                $bookings->customer_sname = $request->customer_sname;
                $bookings->customer_tel = $request->customer_tel;
                $bookings->workfield = $request->workfield;
                $bookings->team = $request->agent;
                $bookings->team_tel = $request->agent_phone;
                $bookings->booking_status = $request->status_id;
                $bookings->bid_date = $request->bid_date;
                $bookings->booking_date = $request->booking_date;
                $bookings->booking_contract = $request->booking_contract;
                $bookings->mortgage_date = $request->mortgage_date;
                $bookings->remark = $request->remark;
                $bookings->deposit = $request->deposit;
                $bookings->save();
                $bookings->refresh();


                Log::addLog($request->user_id, json_encode($rooms_old), 'Update Status Room : ' . $rooms);
                Log::addLog($request->user_id, '', 'Create Booking Room : ' . $bookings);

                return response()->json([
                    'message' => 'เพิ่มข้อมูลสำเร็จ'
                ], 201);

                return redirect()->back();
            // }
        } else {
            //Alert::error('Error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
            return response()->json(['error' => $validator->errors()]);
            //return redirect()->back();

        }
    }

    public function editBooking($id)
    {

        $bookings = Booking::where('id', $id)->first();

        return response()->json($bookings);
    }

    public function updateBookingRoom(Request $request, $id)
    {
        //dd($request->all());

        $baseRules = [
            'customer_fname' => 'required',
            'customer_sname' => 'required',
            'customer_tel' => 'required',
            'agent' => 'required',
            'agent_phone' => 'required',
        ];

        $baseMessages = [
            'customer_fname.required' => 'กรอก ชื่อ ',
            'customer_sname.required' => 'กรอก นามสกุล',
            'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
            'agent.required' => 'กรอก ทีม',
            'agent_phone.required' => 'กรอก เบอร์สายงาน',
        ];

        // if Status Selected
        // 8 เสนอราคา
        // 2 จอง
        // 3 ทำสัญญา
        $statusSpecificRules = [
            '8' => ['bid_date_edit' => 'required'],
            '2' => ['booking_date_edit' => 'required', 'deposit' => 'required'],
            '3' => ['booking_contract_edit' => 'required'],
        ];


        $rules = array_merge($baseRules, $statusSpecificRules[$request->status_id_edit] ?? []);
        $messages = array_merge($baseMessages, [
            'bid_date_edit.required' => 'กรอก วันที่',
            'booking_date_edit.required' => 'กรอก วันที่',
            'deposit.required' => 'กรอก งินจอง',
            'booking_contract_edit.required' => 'กรอก วันที่',
        ]);

        // Perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Alert::error('Error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
            return response()->json(['error' => $validator->errors()]);
            return redirect()->back();
        }

        // if ($request->status_id == "8") { //เสนอราคา
        //     $validator = Validator::make($request->all(), [
        //         'customer_fname' => 'required',
        //         'customer_sname' => 'required',
        //         'customer_tel' => 'required',
        //         'agent' => 'required',
        //         'agent_phone' => 'required',
        //         'bid_date' => 'required',
        //     ], [
        //         'customer_fname.required' => 'กรอก ชื่อ ',
        //         'customer_sname.required' => 'กรอก นามสกุล',
        //         'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
        //         'agent.required' => 'กรอก ทีม',
        //         'agent_phone.required' => 'กรอก เบอร์สายงาน',
        //         'bid_date_edit.required' => 'กรอก วันที่',
        //     ]);
        // } elseif ($request->status_id == "2") { //จองแล้ว
        //     $validator = Validator::make($request->all(), [
        //         'customer_fname' => 'required',
        //         'customer_sname' => 'required',
        //         'customer_tel' => 'required',
        //         'agent' => 'required',
        //         'agent_phone' => 'required',
        //         'booking_date_edit' => 'required',
        //         'deposit' => 'required',
        //     ], [
        //         'customer_fname.required' => 'กรอก ชื่อ ',
        //         'customer_sname.required' => 'กรอก นามสกุล',
        //         'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
        //         'agent.required' => 'กรอก ทีม',
        //         'agent_phone.required' => 'กรอก เบอร์สายงาน',
        //         'booking_date_edit.required' => 'กรอก วันที่',
        //         'deposit.required' => 'กรอก งินจอง'
        //     ]);
        // } elseif ($request->status_id == "3") { //ทำสัญญา
        //     $validator = Validator::make($request->all(), [
        //         'customer_fname' => 'required',
        //         'customer_sname' => 'required',
        //         'customer_tel' => 'required',
        //         'agent' => 'required',
        //         'agent_phone' => 'required',
        //         'booking_contract_edit' => 'required',
        //     ], [
        //         'customer_fname.required' => 'กรอก ชื่อ ',
        //         'customer_sname.required' => 'กรอก นามสกุล',
        //         'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
        //         'agent.required' => 'กรอก ทีม',
        //         'agent_phone.required' => 'กรอก เบอร์สายงาน',
        //         'booking_contract_edit.required' => 'กรอก วันที่',
        //     ]);
        // } else {
        //     $validator = Validator::make($request->all(), [
        //         'customer_fname' => 'required',
        //         'customer_sname' => 'required',
        //         'customer_tel' => 'required',
        //         'agent' => 'required',
        //         'agent_phone' => 'required',

        //     ], [
        //         'customer_fname.required' => 'กรอก ชื่อ ',
        //         'customer_sname.required' => 'กรอก นามสกุล',
        //         'customer_tel.required' => 'กรอก เบอร์ลูกค้า',
        //         'agent.required' => 'กรอก ทีม',
        //         'agent_phone.required' => 'กรอก เบอร์สายงาน',

        //     ]);
        // }

        if ($validator->passes()) {

            if ($request->status_id_edit == 4) {
                // cancel รายการจองลูกค้าคนอื่น ๆ
                $bookings_cancel = Booking::where('rooms_id', $request->rooms_id)
                    ->where('id', '<>', $id)
                    ->update(['booking_status' => 9]);

                $bookings = Booking::where('id', $id)->first();
                $bookings_old = $bookings->toArray();

                $rooms = Room::where('id', $request->rooms_id)->first();
                $rooms->status_id = $request->status_id_edit;
                $rooms->save();
                $rooms_old = $rooms->toArray();

                $bookings->booking_by = $request->user_id;
                $bookings->customer_fname = $request->customer_fname;
                $bookings->customer_sname = $request->customer_sname;
                $bookings->customer_tel = $request->customer_tel;
                $bookings->workfield = $request->workfield;
                $bookings->team = $request->agent;
                $bookings->team_tel = $request->agent_phone;
                $bookings->booking_status = $request->status_id_edit;
                $bookings->bid_date = $request->bid_date_edit;
                $bookings->booking_date = $request->booking_date_edit;
                $bookings->booking_contract = $request->booking_contract_edit;
                $bookings->mortgage_date = $request->mortgage_date_edit;
                $bookings->save();
                $bookings->refresh();

                Log::addLog($request->user_id, json_encode($bookings_old), 'Update Booking Room : ' . $bookings);
                Log::addLog($request->user_id, json_encode($rooms_old), 'Update Room : ' . $rooms);
            } else {

                //ref status
                // id | name
                // 1 = ห้องว่าง
                // 2 = จองแล้ว
                // 3 = ทำสัญญา
                // 4 = โอน
                // 5 = คืนห้อง
                // 6 = ไม่พร้อมขาย
                // 8 = ออกใบเสนอราคา
                // 9 = ยกเลิก

                // กำสถานะของลูกค้าเป็น Step
                $stepMapping = [
                    '8' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4
                ];



                $rooms = Room::where('id', $request->rooms_id)->first();


                $newStatusStep = $stepMapping[$request->status_id_edit] ?? null;
                $currentStatusStep = $stepMapping[$rooms->status_id] ?? null;

                $bookings = Booking::where('id', $id)->first();
                $bookings_old = $bookings->toArray();

                // ถ้าสถานะใหม่น้อยกว่าสถานะปัจจุบัน, ไม่ทำการอัปเดท
                if ($newStatusStep < $currentStatusStep) {


                    $bookings->booking_by = $request->user_id;
                    $bookings->customer_fname = $request->customer_fname;
                    $bookings->customer_sname = $request->customer_sname;
                    $bookings->customer_tel = $request->customer_tel;
                    $bookings->workfield = $request->workfield;
                    $bookings->team = $request->agent;
                    $bookings->team_tel = $request->agent_phone;
                    $bookings->booking_status = $request->status_id_edit;
                    $bookings->bid_date = $request->bid_date_edit;
                    $bookings->booking_date = $request->booking_date_edit;
                    $bookings->booking_contract = $request->booking_contract_edit;
                    $bookings->mortgage_date = $request->mortgage_date_edit;
                    $bookings->deposit = $request->deposit;
                    $bookings->save();
                    $bookings->refresh();
                    Log::addLog($request->user_id, json_encode($bookings_old), 'Update Booking Room : ' . $bookings);
                } else {

                    $rooms = Room::where('id', $request->rooms_id)->first();
                    $rooms->status_id = $request->status_id_edit;
                    $rooms->save();
                    $rooms_old = $rooms->toArray();

                    $bookings->booking_by = $request->user_id;
                    $bookings->customer_fname = $request->customer_fname;
                    $bookings->customer_sname = $request->customer_sname;
                    $bookings->customer_tel = $request->customer_tel;
                    $bookings->workfield = $request->workfield;
                    $bookings->team = $request->agent;
                    $bookings->team_tel = $request->agent_phone;
                    $bookings->booking_status = $request->status_id_edit;
                    $bookings->bid_date = $request->bid_date_edit;
                    $bookings->booking_date = $request->booking_date_edit;
                    $bookings->booking_contract = $request->booking_contract_edit;
                    $bookings->mortgage_date = $request->mortgage_date_edit;
                    $bookings->deposit = $request->deposit;
                    $bookings->save();
                    $bookings->refresh();

                    Log::addLog($request->user_id, json_encode($rooms_old), 'Update Room : ' . $rooms);
                    Log::addLog($request->user_id, json_encode($bookings_old), 'Update Booking Room : ' . $bookings);
                }
            }

            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {
            //Alert::error('Error', 'กรุณากรอกข้อมูลให้ครบถ้วน');
            return response()->json(['error' => $validator->errors()]);
            //return redirect()->back();

        }
    }

    public function getBookingCustomer($id)
    {
        // $bookings = Booking::
        // leftJoin('status_rooms','status_rooms.id','bookings.booking_status')
        // ->select('bookings.*','status_rooms.name as status')
        // ->where('rooms_id',$id)->get();



        $bookings = Booking::leftJoin('status_rooms', 'status_rooms.id', 'bookings.booking_status')
            ->leftJoin('rooms', 'rooms.id', 'bookings.rooms_id')
            ->select('bookings.*', 'status_rooms.name as status', 'rooms.address', 'rooms.room_address')
            ->where('rooms_id', $id)->where('booking_status', '<=', 8)
            ->get();

        foreach ($bookings as &$booking) {
            if ($booking->booking_status == 8) { //สถานะใบเสนอราคา
                $dayExp = 14;
                $bidDate = Carbon::parse($booking->bid_date);
                $currentDate = Carbon::now();
                $daysPassed = $bidDate->diffInDays($currentDate);

                // ตรวจสอบว่า daysPassed มากกว่า $dayExp หรือไม่
                if ($daysPassed > $dayExp) {
                    $booking->daysOverdue = $daysPassed - $dayExp;  // คำนวณวันที่เกินกำหนด
                } else {
                    $booking->daysOverdue = 0;  // ถ้าไม่เกินกำหนดให้กำหนดเป็น null
                }

                $booking->daysPassed = $daysPassed;
            } elseif ($booking->booking_status == 2) { //สถานะจองแล้ว
                $dayExp = 7;
                $bookingDate = Carbon::parse($booking->booking_date);
                $currentDate = Carbon::now();
                $daysPassed = $bookingDate->diffInDays($currentDate);

                // ตรวจสอบว่า daysPassed มากกว่า $dayExp หรือไม่
                if ($daysPassed > $dayExp) {
                    $booking->daysOverdue = $daysPassed - $dayExp;  // คำนวณวันที่เกินกำหนด
                } else {
                    $booking->daysOverdue = 0;  // ถ้าไม่เกินกำหนดให้กำหนดเป็น null
                }

                $booking->daysPassed = $daysPassed;
            } elseif ($booking->booking_status == 3) { //สถานะทำสัญญา
                $dayExp = 30;
                $contractDate = Carbon::parse($booking->booking_contract);
                $currentDate = Carbon::now();
                $daysPassed = $contractDate->diffInDays($currentDate);

                // ตรวจสอบว่า daysPassed มากกว่า $dayExp หรือไม่
                if ($daysPassed > $dayExp) {
                    $booking->daysOverdue = $daysPassed - $dayExp;  // คำนวณวันที่เกินกำหนด
                } else {
                    $booking->daysOverdue = 0;  // ถ้าไม่เกินกำหนดให้กำหนดเป็น null
                }

                $booking->daysPassed = $daysPassed;
            } else {
                $booking->daysPassed = 0;
                $booking->daysOverdue = 0;
            }
        }

        return response()->json($bookings, 200);
    }



    public function cancelBooking(Request $request, $id, $user_id, $roomId)
    {

        //เช๊คถ้ามีการยกเลิก แล้วไม่มี ลูกค้าอื่น ๆ ที่มีสถานะ ใบเสนอราคา / จอง / ทำสัญญา / โอน
        //ให้อัพเดทสถานะห้อง เป็นว่าง

        $bookingStatuses = ['2', '3', '4', '8'];
        $checkStatus = Booking::where('rooms_id', $roomId)
            ->whereIn('booking_status', $bookingStatuses)
            ->count();
        //dd($checkStatus);
        if ($checkStatus >= 1) {
            // ปรับสถานะห้องเป็นว่าง
            $rooms = Room::with(['project'])->where('id', $roomId)->first();
            $project = $rooms->project->name;
            $rooms_old = $rooms->toArray();

            $rooms->status_id = 1; //ว่าง
            $rooms->save();


            $bookings = Booking::where('id', $id)->first();
            $bookings_old = $bookings->toArray();

            $bookings->booking_by = $user_id;
            $bookings->booking_status = 9; //ยกเลิกจอง
            $bookings->remark = $request->cancellationReason;
            $bookings->save();

            Log::addLog($user_id, json_encode($bookings_old), 'Cancel Booking : ' . $bookings);

            $because = $request->cancellationReason;
            $customer = $bookings->customer_fname . " " . $bookings->customer_sname;
            $roomaddress = $rooms->room_address;

            if ($request->cancellationReason && $request->mail) {

                $toEmail = $request->mail;
                $toBCC = ['noreply@vbeyond.co.th'];

                Mail::send(
                    'rooms.mail',
                    ['customer' => $customer, 'because' => $because, 'project' => $project, 'roomaddress' => $roomaddress],
                    function (Message $message) use ($toEmail, $toBCC) {
                        $message->to($toEmail)
                            ->bcc($toBCC)
                            ->subject('ยกเลิกการจอง');
                    }
                );
            }

            return response()->json([
                'message' => 'ยกเลิกการจองสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {

            // อัพเดทสถานะในตาราง bookings
            $bookings = Booking::where('id', $id)->first();
            $bookings_old = $bookings->toArray();

            $bookings->booking_by = $user_id;
            $bookings->booking_status = 9; //ยกเลิกจอง
            $bookings->remark = $request->cancellationReason;
            $bookings->save();

            Log::addLog($user_id, json_encode($bookings_old), 'Cancel Booking : ' . $bookings);

            return response()->json([
                'message' => 'ยกเลิกการจองสำเร็จ'
            ], 201);

            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id, $user_id)
    {

        // $bookings = Booking::where('id', $id)->first();
        // Log::addLog($user_id, '', 'Delete Booking : ', $bookings);
        // $bookings->delete($id);
        // return response()->json([
        //     'message' => 'ลบข้อมูลสำเร็จ'
        // ], 201);

        $bookings = Booking::where('id', $id)->first();
        if ($bookings) {
            // ทำการตรวจสอบว่ามีการจองอื่นๆ อยู่ในห้องนี้อีกหรือไม่
            $hasOtherBookings = Booking::where('rooms_id', $bookings->rooms_id)->where('id', '!=', $id)->exists();

            // ถ้าไม่มีการจองอื่น, ปรับสถานะห้องเป็น "ว่าง"
            if (!$hasOtherBookings) {
                $rooms = Room::where('id', $bookings->rooms_id)
                    ->update(['status_id' => 1]);
                Log::addLog($user_id, '', 'Update Status Rooms : ', $rooms);
            }

            Log::addLog($user_id, '', 'Delete Booking : ', $bookings);

            $bookings->delete($id);
            return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ'
            ], 201);
        } else {
            return response()->json([
                'message' => 'ไม่พบข้อมูลการจองที่ต้องการลบ'
            ], 404);
        }
    }


    public function createSLA(Request $request)
    {
        //สถานะเสนอราคา
        $sla_bid = 14;
        $bookings_sla = Booking::leftJoin('status_rooms', 'status_rooms.id', 'bookings.booking_status')
            ->select('bookings.*', 'status_rooms.name as status', DB::raw('DATEDIFF(CURDATE(), bookings.bid_date) as days_passed'))
            ->where('booking_status', 8)
            ->havingRaw('days_passed > ?', [$sla_bid])
            ->get();


        //หาจำนวนวันหยุดประจำเดือน
        // $holidayCount = DB::connection('mysql_user')
        //     ->table('holiday')
        //     ->whereRaw('DATE_FORMAT(Holiday, "%Y-%m") = DATE_FORMAT(CURDATE(), "%Y-%m")')
        //     ->count();




        return response()->json(['data' => $bookings_sla], 200);
    }
}
