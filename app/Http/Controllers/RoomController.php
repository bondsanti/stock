<?php

namespace App\Http\Controllers;

use App\Imports\RoomsImport;
use App\Models\Bank;
use App\Models\Facility;
use App\Models\Furniture;
use App\Models\Log;
use App\Models\Plan;
use App\Models\Room;
use App\Models\Project;
use App\Models\Promotion;
use App\Models\Role_user;
use App\Models\Status_Room;
use App\Models\User;
use Session;
use DataTables;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RoomController extends Controller
{
    public function index(Request $request)
    {

        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $dataLoginUser = Session::get('loginId');
        // dd($dataLoginUser);

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);


        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get();
        $furniture = Furniture::orderBy('id', 'asc')->get();
        $facility = Facility::orderBy('id', 'asc')->get();

        //Show Status By Role
        $query = Status_Room::orderBy('id', 'asc');
        if ($isRole->role_type != "SuperAdmin" && $isRole->role_type != "Admin" && $isRole->role_type != "Staff") {
            $query->whereNotIn('name', ['คืนห้อง', 'ยกเลิก']);
        }
        $status = $query->get();



        if ($isRole->role_type != "Partner") {
            return view('rooms.index', compact(
                'dataLoginUser',
                'projects',
                'furniture',
                'facility',
                'status',
                'isRole'
            ));
        } else {
            return view('rooms.partner.index', compact(
                'dataLoginUser',
                'projects',
                'furniture',
                'facility',
                'status',
                'isRole'
            ));
        }
    }

    public function edit($id)
    {

        $message = "";

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId')['user_id'])->first();
        $isRole = Role_user::where('user_id',Session::get('loginId')['user_id'])->first();
        //dd($isRole);

        $rooms = Room::with(['project', 'plan', 'user', 'status'])->where('id', $id)->first();
        $furniture = Furniture::orderBy('id', 'asc')->get();
        $facility = Facility::orderBy('id', 'asc')->get();
        $status = Status_Room::orderBy('id', 'asc')->get();

        $promotions = Promotion::where('project_id', $rooms->project_id)->where('expire', '>', date('Y-m-d'))->get();
        //dd($promotions);

        if ($rooms->status_id == 2 && $rooms->booking_date < date('Y-m-d', strtotime('-7 Days'))) {
            $message = 'เลยเวลาการจอง';
        } else if (
            $rooms->status_id == 4 &&
            $rooms->type_living == '2' &&
            $rooms->guarantee_end != null &&
            $rooms->guarantee_end < date('Y-m-d')
        ) {
            $message = 'หมดการันตี ' . $rooms->guarantee_end;
        }

        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get();

        //API CUSOMTER
        $customers = DB::connection('mysql_report')->table('product')
            ->select('pid', DB::raw('CONCAT(name, " ", bank) as name'), 'bank')
            ->get();
        if ($isRole->role_type == "User" && $isRole->dept == "Finance") {

            return view('rooms.finance.edit', compact(
                'dataLoginUser',
                'rooms',
                'projects',
                'furniture',
                'facility',
                'status',
                'customers',
                'promotions',
                'isRole',
                'message'
            ));
        } else {
            return view('rooms.edit', compact(
                'dataLoginUser',
                'rooms',
                'projects',
                'furniture',
                'facility',
                'status',
                'customers',
                'promotions',
                'isRole',
                'message'
            ));
        }
    }

    public function editPartner($id)
    {

        $message = "";

        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $dataLoginUser = Session::get('loginId');
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);

        $rooms = Room::with(['project', 'plan', 'user', 'status'])->where('id', $id)->first();
        $furniture = Furniture::orderBy('id', 'asc')->get();
        $facility = Facility::orderBy('id', 'asc')->get();
        $status = Status_Room::orderBy('id', 'asc')->get();

        $promotions = Promotion::where('project_id', $rooms->project_id)->where('expire', '>', date('Y-m-d'))->get();
        //dd($promotions);

        if ($rooms->status_id == 2 && $rooms->booking_date < date('Y-m-d', strtotime('-7 Days'))) {
            $message = 'เลยเวลาการจอง';
        } else if (
            $rooms->status_id == 4 &&
            $rooms->type_living == '2' &&
            $rooms->guarantee_end != null &&
            $rooms->guarantee_end < date('Y-m-d')
        ) {
            $message = 'หมดการันตี ' . $rooms->guarantee_end;
        }

        // $projects = Project::orderBy('name', 'asc')->where('active',1)->get()->filter(function ($value) use ($dataLoginUser) {
        // 	if ($dataLoginUser->high_rise == 1 && $dataLoginUser->low_rise == 1) {
        // 		return true;
        // 	}
        // 	if ($dataLoginUser->high_rise == 1 && $value->project->high_rise == 1) {
        // 		return true;
        // 	}
        // 	if ($dataLoginUser->low_rise == 1 && $value->project->low_rise == 1) {
        // 		return true;
        // 	}
        // 	return false;
        // });
        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get();

        $customers = DB::connection('mysql_report')->table('product')
            ->select('pid', DB::raw('CONCAT(name, " ", bank) as name'), 'bank')
            ->get();

        return view('rooms.partner.edit', compact(
            'dataLoginUser',
            'rooms',
            'projects',
            'furniture',
            'facility',
            'status',
            'customers',
            'promotions',
            'isRole',
            'message'
        ));
    }

    public function detail($id)
    {

        $message = "";
        $dataLoginUser = Session::get('loginId');
        // $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);

        $rooms = Room::with(['project', 'plan', 'user', 'status'])->where('id', $id)->first();
        $furniture = Furniture::orderBy('id', 'asc')->get();
        $facility = Facility::orderBy('id', 'asc')->get();
        $status = Status_Room::orderBy('id', 'asc')->get();

        $promotions = Promotion::where('project_id', $rooms->project_id)->where('expire', '>', date('Y-m-d'))->get();
        //dd($promotions);

        if ($rooms->status_id == 2 && $rooms->booking_date < date('Y-m-d', strtotime('-7 Days'))) {
            $message = 'เลยเวลาการจอง';
        } else if (
            $rooms->status_id == 4 &&
            $rooms->type_living == '2' &&
            $rooms->guarantee_end != null &&
            $rooms->guarantee_end < date('Y-m-d')
        ) {
            $message = 'หมดการันตี ' . $rooms->guarantee_end;
        }

        // $projects = Project::orderBy('name', 'asc')->where('active',1)->get()->filter(function ($value) use ($dataLoginUser) {
        // 	if ($dataLoginUser->high_rise == 1 && $dataLoginUser->low_rise == 1) {
        // 		return true;
        // 	}
        // 	if ($dataLoginUser->high_rise == 1 && $value->project->high_rise == 1) {
        // 		return true;
        // 	}
        // 	if ($dataLoginUser->low_rise == 1 && $value->project->low_rise == 1) {
        // 		return true;
        // 	}
        // 	return false;
        // });
        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get();

        $customers = DB::connection('mysql_report')->table('product')
            ->select('pid', DB::raw('CONCAT(name, " ", bank) as name'), 'bank')
            ->get();

        return view('rooms.detail', compact(
            'dataLoginUser',
            'rooms',
            'projects',
            'furniture',
            'facility',
            'status',
            'customers',
            'promotions',
            'isRole',
            'message'
        ));
    }


    //Form Dashboard
    public function updateExpire(Request $request)
    {
        $room = Room::find($request->id);

        if (!$room) {
            return response()->json('');
        }

        DB::connection('mysql_report')->table('product')->where('pid', $room->pid)->update([
            'RoomNo' => '',
            'Homeno' => '',
            'project_id' => 0,
        ]);
        $room->user_name = null;
        $room->pid = null;
        $room->status_id = 1;
        $room->save();

        return response()->json($room);
    }

    //Query By Project Relation
    public function getPlan($id)
    {

        $plans = Plan::where('project_id', $id)->get();

        return response()->json([
            'plans' => $plans
        ]);
    }

    public function getCustomers(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 1000);

        $customers = DB::connection('mysql_report')
            ->table('product')
            ->select('pid', DB::raw('CONCAT(name, " ", bank) as name'), 'bank')
            ->skip($offset)
            ->take($limit)
            ->get();

        // $customers = DB::connection('mysql_report')
        // ->table('product')
        // ->select('pid', DB::raw('CONCAT(name, " ", bank) as name'), 'bank')
        // ->get();

        return response()->json($customers);
    }

    public function store(Request $request)
    {

        //เช็คห้องซ้ำ
        $existingRoom = Room::where('project_id', $request->project_id)->where('address', $request->address)->where('room_address', $request->room_address)->first();

        if ($existingRoom) {
            return response()->json([
                'message' => 'ห้องนี้มีอยู่แล้ว'
            ], 400);
        }


        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'plan_id' => 'required',
            'room_type' => 'required',
            'floor' => 'required',
            'building' => 'required',
            'room_address' => 'required',
            'address' => 'required',
            'direction' => 'required',
            'key' => 'required',
            'keycard' => 'required',
            'price' => 'required',
            'area' => 'required',

        ], [
            'project_id.required' => 'เลือก โครงการ',
            'plan_id.required' => 'เลือก Plan',
            'room_type.required' => 'เลือก Type',
            'floor.required' => 'กรอก ชั้น',
            'building.required' => 'กรอก ตึก',
            'room_address.required' => 'กรอก ห้องเลขที่',
            'address.required' => 'กรอก บ้านเลขที่',
            'direction.required' => 'กรอก ทิศ/ฝั่ง',
            'key.required' => 'กรอก จำนวนกุญแจ',
            'keycard.required' => 'กรอก จำนวนคีย์การ์ด P/B ',
            'price.required' => 'กรอก ราคาห้อง',
            'area.required' => 'กรอก ขนาด',
        ]);

        if ($validator->passes()) {

            $room = new Room();
            $room->project_id = $request->project_id;
            $room->plan_id = $request->plan_id;
            $room->room_type = $request->room_type;
            $room->floor = $request->floor;
            $room->building = $request->building;
            $room->room_address = $request->room_address;
            $room->address = $request->address;
            $room->direction = $request->direction;
            $room->key = $request->key;
            $room->keycard = $request->keycard;
            $room->price = $request->price;
            $room->sqm_price = round($request->price / $request->area, 2);
            $room->area = $request->area;
            $room->save();
            // $room->refresh();

            $furnitureValues = $request->furniture;
            $facilityValues = $request->facility;

            $room->furnitures()->attach($furnitureValues);
            $room->facilities()->attach($facilityValues);
            // foreach ($furnitureValues as $furniture_id) {
            //     DB::connection('mysql')->table('furniture_room')->insert([
            //         'furniture_id' => $furniture_id,
            //         'room_id' => $room->id
            //     ]);
            // }

            // foreach ($facilityValues as $facility_id) {

            //     DB::connection('mysql')->table('facility_room')->insert([
            //         'facility_id' => $facility_id,
            //         'room_id' => $room->id
            //     ]);
            // }


            // สร้าง log การ insert โดยใช้ addLog
            Log::addLog($request->user_id, '', 'Create Room : ' . $room);

            return response()->json([
                'message' => 'เพิ่มข้อมูลสำเร็จ'
            ], 201);

            return redirect()->back();
        } else {

            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $rooms = Room::where('id', $request->room_id)->first();

        $projects = DB::connection('mysql_report')
            ->table('project')
            ->where('pid', $request->project_id)
            ->first();

        $rooms_old = $rooms->toArray();

        $rooms->project_id = $request->project_id;
        $rooms->plan_id = $request->plan_id;
        $rooms->room_type = $request->type_id;
        $rooms->floor = $request->floor;
        $rooms->building = $request->building;
        $rooms->room_address = $request->room_address;
        $rooms->address = $request->address;
        $rooms->direction = $request->direction;
        $rooms->key = $request->key;
        $rooms->keycard = $request->keycard;
        $rooms->type_living = $request->type_living;
        $rooms->special_price1 = $request->special_price1;
        $rooms->special_price2 = $request->special_price2;
        $rooms->special_price3 = $request->special_price3;
        $rooms->special_price4 = $request->special_price4;
        $rooms->special_price5 = $request->special_price5;
        $rooms->special_price_team = $request->special_price_team;
        $rooms->promotion_id = $request->promotion_id;
        $rooms->price = $request->price;
        $rooms->area = $request->area;
        $rooms->sqm_price = round($request->price / $request->area, 2);
        $rooms->meter_code = $request->meter_code;
        $rooms->electric_contract = $request->electric_contract;
        $rooms->staffproj = $request->staffproj;
        $rooms->fixseller = $request->fixseller;
        $rooms->remarkshow = $request->remarkshow;
        $rooms->remark = $request->remark;
        $rooms->status_id = $request->status_id;

        $furnitureValues = $request->furniture;
        $facilityValues = $request->facility;

        $existingFurnitureIds = DB::connection('mysql')->table('furniture_room')
            ->where('room_id', $rooms->id)
            ->pluck('furniture_id')
            ->toArray();


        $existingFacilityIds = DB::connection('mysql')->table('facility_room')
            ->where('room_id', $rooms->id)
            ->pluck('facility_id')
            ->toArray();

        if ($furnitureValues) {
            DB::connection('mysql')->table('furniture_room')
                ->where('room_id', $rooms->id)
                ->whereNotIn('furniture_id', $furnitureValues)
                ->delete();
            foreach ($furnitureValues as $furniture_id) {
                if (!in_array($furniture_id, $existingFurnitureIds)) {
                    DB::connection('mysql')->table('furniture_room')->insert([
                        'furniture_id' => $furniture_id,
                        'room_id' => $rooms->id
                    ]);
                }
            }
        }

        if ($facilityValues) {
            DB::connection('mysql')->table('facility_room')
                ->where('room_id', $rooms->id)
                ->whereNotIn('facility_id', $facilityValues)
                ->delete();

            foreach ($facilityValues as $facility_id) {
                if (!in_array($facility_id, $existingFacilityIds)) {
                    DB::connection('mysql')->table('facility_room')->insert([
                        'facility_id' => $facility_id,
                        'room_id' => $rooms->id
                    ]);
                }
            }
        }

        $rooms->save();

        Log::addLog($request->user_id, json_encode($rooms_old), 'Update Room : ' . $rooms);


        Alert::success('Success', 'อัพเดทข้อมูลสำเร็จ!');
        // return redirect()->back();
        return redirect(route('room'));
    }

    public function search(Request $request)
    {
        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $dataLoginUser = Session::get('loginId');

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);

        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get();
        $furniture = Furniture::orderBy('id', 'asc')->get();
        $facility = Facility::orderBy('id', 'asc')->get();

        $query = Status_Room::orderBy('id', 'asc');
        if ($isRole->role_type != "SuperAdmin" && $isRole->role_type != "Admin"  && $isRole->role_type != "Staff") {
            $query->whereNotIn('name', ['คืนห้อง', 'ยกเลิก']);
        }
        $status = $query->get();


        // $rooms = Room::with(['project', 'plan', 'user', 'status', 'booking'])
        //     ->whereHas('project', function ($query) {
        //         $query->where('active', 1);
        //     });
            $rooms = Room::with(['project', 'plan', 'status', 'booking'])
            ->whereHas('project', function ($query) {
                $query->where('active', 1);
            });

        if ($request->project_id != "ทั้งหมด") {
            $rooms->where('project_id', $request->project_id);
        }

        // if ($request->user_name) {
        //     $rooms->where(function ($query) use ($request) {
        //         $query->where('owner', 'LIKE', '%' . $request->user_name . '%')
        //             ->orWhereHas('booking', function ($subQuery) use ($request) {
        //                 $subQuery->where('customer_fname', 'LIKE', '%' . $request->user_name . '%')
        //                     ->orWhere('customer_sname', 'LIKE', '%' . $request->user_name . '%');
        //             });
        //     });
        // }

        if ($request->room_address) {
            $rooms->where('room_address', 'LIKE', '%' . $request->room_address . '%');
        }

        if ($request->address) {
            $rooms->where('address', 'LIKE', '%' . $request->address . '%');
        }

        if ($request->phone) {
            $rooms->where(function ($query) use ($request) {
                $query->where('phone', 'LIKE', '%' . $request->phone . '%')
                    ->orWhereHas('booking', function ($subQuery) use ($request) {
                        $subQuery->where('customer_tel', 'LIKE', '%' . $request->phone . '%');
                    });
            });
        }

        if ($request->agent) {
            $rooms->where(function ($query) use ($request) {
                $query->where('agent', 'LIKE', '%' . $request->agent . '%')
                    ->orWhereHas('booking', function ($subQuery) use ($request) {
                        $subQuery->where('team', 'LIKE', '%' . $request->agent . '%');
                    });
            });
        }

        if ($request->fixseller) {
            $rooms->where('fixseller', 'LIKE', '%' . $request->fixseller . '%');
        }

        if ($request->startprice) {
            $rooms->where('price', '>=', $request->startprice);
        }

        if ($request->startprice && $request->endprice) {
            $rooms->whereBetween('price', [$request->startprice, $request->endprice]);
        } elseif (empty($request->startprice) && $request->endprice) {
            $rooms->whereBetween('price', [0, $request->endprice]);
        }

        if ($request->dateselect && $request->startdate) {
            //dd($request->enddate);
            if ($request->dateselect == "mortgaged_date") {
                $rooms->WhereHas('booking', function ($subQuery) use ($request) {
                    if ($request->enddate != null) {
                        $subQuery->whereBetween('mortgage_date', [$request->startdate, $request->enddate]);
                    } else {
                        $subQuery->whereBetween('mortgage_date', [$request->startdate, $request->startdate]);
                    }
                });
            } elseif ($request->dateselect == "bid_date") {
                $rooms->whereHas('booking', function ($subQuery) use ($request) {
                    if ($request->enddate != null) {
                        $subQuery->whereBetween('bid_date', [$request->startdate, $request->enddate]);
                    } else {
                        $subQuery->whereBetween('bid_date', [$request->startdate, $request->startdate]);
                    }
                });
            } elseif ($request->dateselect == "booking_date") {
                $rooms->WhereHas('booking', function ($subQuery) use ($request) {
                    if ($request->enddate != null) {
                        $subQuery->whereBetween('booking_date', [$request->startdate, $request->enddate]);
                    } else {
                        $subQuery->whereBetween('booking_date', [$request->startdate, $request->startdate]);
                    }
                });
            } elseif ($request->dateselect == "contract_date") {
                $rooms->WhereHas('booking', function ($subQuery) use ($request) {
                    if ($request->enddate != null) {
                        $subQuery->whereBetween('booking_contract', [$request->startdate, $request->enddate]);
                    } else {
                        $subQuery->whereBetween('booking_contract', [$request->startdate, $request->startdate]);
                    }
                });
            }
        }
        // dd($rooms);
        if ($request->status) {
            $rooms->whereIn('status_id', $request->status);
        }
        $rooms = $rooms->orderBy('room_address', 'asc')->get();
        $roomsCount = $rooms->count();

        $formInputs = $request->all();

        $viewName = ($isRole->role_type != "Partner") ? 'rooms.search' : 'rooms.search_partner';

        return view($viewName, compact(
            'dataLoginUser',
            'projects',
            'furniture',
            'facility',
            'status',
            'rooms',
            'formInputs',
            'isRole',
            'roomsCount'
        ));
    }

    public function searchPartner(Request $request)
    {
        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $dataLoginUser = Session::get('loginId');

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);


        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get();
        $furniture = Furniture::orderBy('id', 'asc')->get();
        $facility = Facility::orderBy('id', 'asc')->get();
        $query = Status_Room::orderBy('id', 'asc');
        $query->whereNotIn('name', ['คืนห้อง', 'ยกเลิก']);

        $status = $query->get();


        $rooms = Room::with(['project', 'plan', 'user', 'status', 'booking'])
            ->whereHas('project', function ($query) {
                $query->where('active', 1);
            });

        if ($request->project_id !== "ทั้งหมด") {
            $rooms->where('project_id', $request->project_id);
        }

        $rooms->when($request->user_name, function ($query) use ($request) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('owner', 'LIKE', '%' . $request->user_name . '%')
                    ->orWhereHas('booking', function ($bookingSubQuery) use ($request) {
                        $bookingSubQuery->where('customer_fname', 'LIKE', '%' . $request->user_name . '%')
                            ->orWhere('customer_sname', 'LIKE', '%' . $request->user_name . '%');
                    });
            });
        });

        $rooms->when($request->room_address, function ($query) use ($request) {
            $query->where('room_address', 'LIKE', '%' . $request->room_address . '%');
        });

        $rooms->when($request->address, function ($query) use ($request) {
            $query->where('address', 'LIKE', '%' . $request->address . '%');
        });

        $rooms->when($request->phone, function ($query) use ($request) {
            $query->where(function ($subQuery) use ($request) {
                $subQuery->where('phone', 'LIKE', '%' . $request->phone . '%')
                    ->orWhereHas('booking', function ($bookingSubQuery) use ($request) {
                        $bookingSubQuery->where('customer_tel', 'LIKE', '%' . $request->phone . '%');
                    });
            });
        });

        $rooms->when($request->status_pi, function ($query) use ($request) {
            $query->whereHas('booking', function ($subQuery) use ($request) {
                $subQuery->where('team', 'LIKE', '%' . $request->status_pi . '%');
            });
        });

        $rooms->when($request->fixseller, function ($query) use ($request) {
            $query->where('fixseller', 'LIKE', '%' . $request->fixseller . '%');
        });

        $rooms->when($request->startprice, function ($query) use ($request) {
            $query->where('price', '>=', $request->startprice);

            $query->when($request->endprice, function ($subQuery) use ($request) {
                $subQuery->whereBetween('price', [$request->startprice, $request->endprice]);
            });
        });

        if ($request->dateselect && $request->startdate) {
            $rooms->when($request->dateselect == "mortgaged_date" && $request->enddate, function ($query) use ($request) {
                $query->whereBetween('mortgaged_date', [$request->startdate, $request->enddate])
                    ->orWhereHas('booking', function ($subQuery) use ($request) {
                        $subQuery->whereBetween('mortgage_date', [$request->startdate, $request->enddate]);
                    });
            });

            $rooms->when($request->dateselect == "bid_date" && $request->enddate, function ($query) use ($request) {
                $query->whereHas('booking', function ($subQuery) use ($request) {
                    $subQuery->whereBetween('bid_date', [$request->startdate, $request->enddate]);
                });
            });

            $rooms->when($request->dateselect == "booking_date" && $request->enddate, function ($query) use ($request) {
                $query->whereBetween('booking_date', [$request->startdate, $request->enddate])
                    ->orWhereHas('booking', function ($subQuery) use ($request) {
                        $subQuery->whereBetween('booking_date', [$request->startdate, $request->enddate]);
                    });
            });

            $rooms->when($request->dateselect == "contract_date" && $request->enddate, function ($query) use ($request) {
                $query->whereBetween('contract_date', [$request->startdate, $request->enddate])
                    ->orWhereHas('booking', function ($subQuery) use ($request) {
                        $subQuery->whereBetween('booking_contract', [$request->startdate, $request->enddate]);
                    });
            });
        }

        if ($request->status) {
            $rooms->whereIn('status_id', $request->status);
        }



        $rooms = $rooms->orderBy('room_address', 'asc')->get();
        $roomsCount = $rooms->count();
        $formInputs = $request->all();

        $viewName = ($isRole->role_type != "Partner") ? 'rooms.search' : 'rooms.partner.search';

        return view($viewName, compact(
            'dataLoginUser',
            'projects',
            'furniture',
            'facility',
            'status',
            'rooms',
            'formInputs',
            'isRole',
            'roomsCount'
        ));
    }

    public function importexcel(Request $request)
    {
        try {
            Excel::import(new RoomsImport, $request->file('import_file'));
            $rooms = Room::where('status_id', 0)
                ->where('created_at', '=', Room::where('status_id', 0)->max('created_at'))->get();

            $roomsCount = $rooms->count();
            // dd($roomsCount);
            //ส่ง Mail ห้องที่ต้องให้อนุมัติ
            if ($roomsCount > 0) {
                Log::addLog($request->user_id, '', 'Import Room Excel');

                $roomsID = Room::where('status_id', 0)
                    ->where('created_at', '=', Room::where('status_id', 0)->max('created_at'))->first();
                if ($roomsID && $roomsID->status_id === 0) {
                    // ส่งอีเมล์ได้
                    $project = Project::where('id', '=', $roomsID->project_id)->first();
                    $url = "http://vbstock.vbeyond.co.th";
                    // $toEmail = ['santi.c@vbeyond.co.th'];
                    // $toCC = ['sirawich.t@vbeyond.co.th'];
                    // $toEmail = ['santi.c@vbeyond.co.th'];
                    // $toCC = ['noreply@vbeyond.co.th'];
                    $toEmail = ['surasak.p@vbeyond.co.th'];
                    $toCC = ['sirikwan.l@vbeyond.co.th'];
                    $toBCC = ['noreply@vbeyond.co.th'];

                    Mail::send(
                        'rooms.finance.mail',
                        ['Link' => $url, 'roomsCount' => $roomsCount, 'project' => $project->name],
                        function (Message $message) use ($toEmail, $toCC, $toBCC) {
                            $message->to($toEmail)
                                ->cc($toCC)
                                ->bcc($toBCC)
                                ->subject('ห้องรอการอนุมัติ');
                        }
                    );
                }
            }

            // Success message
            Alert::success('Success', 'สำเร็จ!');
            return redirect(route("room"));
        } catch (\Exception $e) {

            Alert::error('Error', 'ไม่สามารถ Import ห้องได้! มี room_address ซ้ำ');
            return redirect(route("room"));
        }
    }



    public function updateexcel(Request $request)
    {
        $excel = Excel::toCollection(new RoomsImport(), request()->file('import_file2'));

        foreach ($excel[0] as $row) {
            if (
                empty($row['id']) ||
                empty($row['price']) ||
                empty($row['area'])
            ) {
                Alert::error('Error', 'มีค่าว่างใน ID, Price หรือ Area!');
                return redirect()->back();
            }

            if (Room::where('id', $row['id'])->count() == 0) {
                Alert::question('Question', 'ไม่พบ ID ห้อง!');
                return redirect()->back();
            } else {
                $total = round($row['price'] / $row['area']);
                $update = Room::where('id', $row['id'])->update([
                    'price' => $row['price'],
                    'area' => $row['area'],
                    'sqm_price' => $total,
                    'special_price1' => $row['special_price1'],
                    'special_price2' => $row['special_price2'],
                    'special_price3' => $row['special_price3'],
                ]);

                Log::addLog($request->user_id, '', 'Update Room Excel : ' . $update);
            }
        }

        Alert::success('Success', 'สำเร็จ!');
        // return redirect()->back();
        return redirect(route("room"));
    }

    public function deleteSelected(Request $request)
    {
        $dataLoginUser = Session::get('loginId');
        // $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $user_id = $dataLoginUser['user_id'];
        $selectedRooms = $request->input('selected_rooms', []);


        // Fetch
        $deletedRooms = Room::whereIn('id', $selectedRooms)->get();

        // Delete the selected
        DB::table('furniture_room')->whereIn('room_id', $selectedRooms)->delete();
        DB::table('facility_room')->whereIn('room_id', $selectedRooms)->delete();
        Room::whereIn('id', $selectedRooms)->delete();


        // Log the deletion with  details
        foreach ($deletedRooms as $room) {
            Log::addLog($user_id, '', 'Delete Room - room_id: ' . $room->id . ', project_id: ' . $room->project_id);
        }

        return response()->json([
            'message' => 'ลบรายการที่เลือกเรียบร้อยแล้ว',
        ], 201);
    }

    public function cancelSelected(Request $request)
    {
        $dataLoginUser = Session::get('loginId');
        //dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $user_id = $dataLoginUser['user_id'];
        $selectedRooms = $request->input('selected_rooms', []);


        // Fetch
        $cancelRooms = Room::whereIn('id', $selectedRooms)->get();


        Room::whereIn('id', $selectedRooms)->update(['status_id' => 5]);


        // Log the deletion with  details
        foreach ($cancelRooms as $room) {
            Log::addLog($user_id, '', 'Cancal Room - room_id: ' . $room->id . ', project_id: ' . $room->project_id);
        }

        return response()->json([
            'message' => 'ลบ/คืนห้องเรียบร้อย!',
        ], 201);
    }

    public function cancelBooking(Request $request, $id, $user_id)
    {
        $rooms = Room::where('id', $id)->first();
        $rooms_old = $rooms->toArray();

        if ($rooms->pid) {
            DB::connection('mysql_report')->table('product')->where('pid', $rooms->pid)->update([
                'RoomNo' => '',
                'Homeno' => '',
                'project_id' => 0,
            ]);
        }
        $rooms->user_name = null;
        $rooms->name = null;
        $rooms->pid = null;
        $rooms->status_id = 1;
        $rooms->remarkshow = null;
        $rooms->sladay = null;
        $rooms->agent_phone = null;
        $rooms->workfield = null;
        $rooms->agent = null;
        $rooms->owner = null;
        $rooms->phone = null;
        $rooms->booking_date = null;
        $rooms->remark_stock = null;
        $rooms->out = 0;
        $rooms->save();

        Log::addLog($user_id, json_encode($rooms_old), 'Cancel Booking : ' . $rooms);

        return response()->json([
            'message' => 'ยกเลิกการจองสำเร็จ'
        ], 201);

        return redirect()->back();
    }

    public function updatePartner(Request $request)
    {

        $rooms = Room::where('id', $request->room_id)->first();

        $rooms_old = $rooms->toArray();

        $rooms->status_work = $request->status_work;
        $rooms->booking_money_singer = $request->booking_money_singer;
        $rooms->sale_name_singer = $request->sale_name_singer;
        $rooms->save();

        Log::addLog($request->user_id, json_encode($rooms_old), 'Partner Update Room : ' . $rooms);


        Alert::success('Success', 'อัพเดทข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function retakeRoomToRent(Request $request, $id, $user_id)
    {
        $rooms = Room::with(['project', 'plan', 'user', 'status'])->where('id', $id)->first();
        $rooms_old = $rooms->toArray();
        $this->newRentRoom($rooms, 'เบิกจ่ายล่วงหน้า');

        return response()->json([
            'message' => 'ทำรายการสำเร็จ!'
        ], 201);

        return redirect()->back();
    }

    public function newRentRoom($rooms, $status = 'การันตี')
    {
        if (Rent::where('HomeNo', $rooms->address)->where('RoomNo', $rooms->room_address)->count() == 0) {

            $rent = new Rent;
            $rent->Create_Date = date('Y-m-d');
            $rent->pid = $rooms->project_id;
            $rent->HomeNo = $rooms->address;
            // $rent->HomeNo = 'test';
            $rent->RoomNo = $rooms->room_address;
            $rent->Building = $rooms->building;
            $rent->Floor = $rooms->floor;
            $rent->RoomType = $rooms->plan->name;
            $rent->Size = $rooms->area;
            $rent->Location = $rooms->direction;
            $rent->Status_Room = 'รอตรวจ';
            $rent->Owner = $rooms->owner;
            $rent->Phone = $rooms->phone;
            $rent->Guarantee_Startdate = $rooms->guarantee_start;
            $rent->Guarantee_Enddate = $rooms->guarantee_end;
            $rent->Guarantee_Amount = $rooms->guarantee_price;
            $rent->RoomKey = $rooms->key;
            $rent->KeyCard = $rooms->keycard;
            $rent->KeyCard = $rooms->keycard;
            $rent->Electric_Contract = $rooms->electric_contract;
            $rent->Meter_Code = $rooms->meter_code;
            $rent->DefectStatus = 'รอตรวจ';
            $date = date_diff(date_create($rooms->guarantee_start), date_create($rooms->guarantee_end));
            $rent->Guarantee_Contract = $date->m;
            $rent->rental_status = $status;

            // 1.การันตี
            // 2.เบิกจ่ายล่วงหน้า
            // 3.ฝากต่อหักภาษี
            // 4.ฝากต่อไม่หักภาษี
            // 5.ฝากเช่า
            $rent->save();
        }
    }

    public function updateOld(Request $request)
    {
        //dd($request->all());
        $rooms = Room::where('id', $request->room_id)->first();

        $projects = DB::connection('mysql_report')
            ->table('project')
            ->where('pid', $request->project_id)
            ->first();

        $rooms_old = $rooms->toArray();


        $restore = 0;
        if ($request->status_id == 1 && $rooms->status_id >= 1) {
            $restore = 1;
        }



        $rooms->project_id = $request->project_id;
        $rooms->plan_id = $request->plan_id;
        $rooms->room_type = $request->type_id;
        $rooms->floor = $request->floor;
        $rooms->building = $request->building;
        $rooms->room_address = $request->room_address;
        $rooms->address = $request->address;
        $rooms->direction = $request->direction;
        $rooms->key = $request->key;
        $rooms->keycard = $request->keycard;

        if ($request->booking_date) {
            $rooms->booking_date = $request->booking_date;
            $rooms->sladay = 0;
        }

        if ($request->contract_date) {
            $rooms->contract_date = $request->contract_date;
            $rooms->sladay = 0;
        }

        $rooms->mortgaged_date = $request->mortgaged_date;

        if ($request->receive_room_date && $request->type_living ==  2) {
            $rooms->receive_room_date = $request->receive_room_date;
            $this->newRentRoom($rooms, 'การันตี');
        }



        $rooms->type_living = $request->type_living;



        //======= pendingCheck ===

        //if ($rooms->type_living == 2 && $rooms->status_id==4) {
        // $rent = DB::connection('mysql_report')->table('rental_room')
        // ->where('HomeNo', $room->address)
        // ->where('RoomNo', $room->room_address)
        // ->first();

        // if (count($rent) > 0 &&  $rent->Status_Room == 'อยู่แล้ว') {
        //     DB::connection('mysql_report')->table('rental_room')
        //         ->where('id', $rent->id)
        //         ->update([
        //             'Guarantee_Startdate' => $room->guarantee_start,
        //             'Guarantee_Enddate' => $room->guarantee_end,
        //         ]);
        // }
        // }

        //======= pendingCheck ===

        //ถ้าเลื่อก Select customers
        if ($request->customer_id > 0) {

            $customers = DB::connection('mysql_report')
                ->table('product')
                ->where('pid', $request->customer_id)
                ->first();

            $rooms->owner = $customers->name;
            $rooms->phone = $customers->tel;
            $rooms->user_name = $customers->name;
            $rooms->name = $customers->name;

            $managers = DB::connection('mysql_report')
                ->table('sale')
                ->where('sid', $customers->sid)
                ->first();

            $rooms->agent = $managers->name;
            if ($managers->leader == "Outsource") {
                $rooms->workfield = 2;
            } elseif ($managers->leader == "Project") {
                $rooms->workfield = 3;
            } else {
                $rooms->workfield = 1;
            }
        } else {

            $rooms->user_name = $request->owner;
            $rooms->owner = $request->owner;
            $rooms->phone = $request->phone;
            $rooms->name = $request->owner;
        }



        if ($request->outsource) {
            $rooms->out = 1;
            $rooms->workfield = $request->workfield;
            $rooms->agent = $request->agent;
            $rooms->agent_phone = $request->agent_phone;
        } else {
            $rooms->out = 0;
            $rooms->workfield = null;
            $rooms->agent = null;
            $rooms->agent_phone = null;
        }

        // $rooms->status_id = $request->status_id;

        $rooms->special_price1 = $request->special_price1;
        $rooms->special_price2 = $request->special_price2;
        $rooms->special_price3 = $request->special_price3;
        $rooms->special_price4 = $request->special_price4;
        $rooms->special_price5 = $request->special_price5;
        $rooms->special_price_team = $request->special_price_team;
        $rooms->promotion_id = $request->promotion_id;
        $rooms->price = $request->price;
        $rooms->area = $request->area;
        $rooms->sqm_price = round($request->price / $request->area, 2);
        $rooms->meter_code = $request->meter_code;
        $rooms->electric_contract = $request->electric_contract;
        $rooms->staffproj = $request->staffproj;
        $rooms->fixseller = $request->fixseller;
        $rooms->remarkshow = $request->remarkshow;
        $rooms->remark = $request->remark;

        $furnitureValues = $request->furniture;
        $facilityValues = $request->facility;


        $existingFurnitureIds = DB::connection('mysql')->table('furniture_room')
            ->where('room_id', $rooms->id)
            ->pluck('furniture_id')
            ->toArray();


        $existingFacilityIds = DB::connection('mysql')->table('facility_room')
            ->where('room_id', $rooms->id)
            ->pluck('facility_id')
            ->toArray();

        if ($furnitureValues) {
            DB::connection('mysql')->table('furniture_room')
                ->where('room_id', $rooms->id)
                ->whereNotIn('furniture_id', $furnitureValues)
                ->delete();
            foreach ($furnitureValues as $furniture_id) {
                if (!in_array($furniture_id, $existingFurnitureIds)) {
                    DB::connection('mysql')->table('furniture_room')->insert([
                        'furniture_id' => $furniture_id,
                        'room_id' => $rooms->id
                    ]);
                }
            }
        }

        if ($facilityValues) {
            DB::connection('mysql')->table('facility_room')
                ->where('room_id', $rooms->id)
                ->whereNotIn('facility_id', $facilityValues)
                ->delete();

            foreach ($facilityValues as $facility_id) {
                if (!in_array($facility_id, $existingFacilityIds)) {
                    DB::connection('mysql')->table('facility_room')->insert([
                        'facility_id' => $facility_id,
                        'room_id' => $rooms->id
                    ]);
                }
            }
        }






        if ($request->status_id == 3 && $request->contract_date == '') {
            $rooms->contract_date = date('Y-m-d');
        }
        $rooms->save();

        //dd($request->customer_id);
        if ($request->status_id == 2 && $request->customer_id) {
            DB::connection('mysql_report')->table('product')->where('pid', $request->customer_id)->update([
                'RoomNo' => $rooms->room_address,
                'Homeno' => $rooms->address,
                'project_id' => $projects->pid,
            ]);

            $product = DB::connection('mysql_report')->table('product')->where('pid', $request->customer_id)->first();
            $rooms->phone = ($product->tel) ? $product->tel : $request->phone;

            $banks = Bank::where('name', 'like', $product->bank)->first();
            $rooms->bank_id = $banks->id;
            $rooms->save();
        }





        Log::addLog($request->user_id, json_encode($rooms_old), 'Update Room : ' . $rooms);
        if ($restore == 1) {
            //dd("*");
            $rooms->status_id = 1;
            $rooms->owner = null;
            $rooms->phone = null;
            $rooms->pid = null;
            $rooms->agent_phone = null;
            $rooms->agent = null;
            $rooms->user_id = null;
            $rooms->user_name = null;
            $rooms->name = null;
            $rooms->remark = null;
            $rooms->remarkshow = null;
            $rooms->staffproj = null;
            $rooms->contract_date = null;
            $rooms->booking_date = null;
            $rooms->sladay = null;
            $rooms->workfield = null;
            $rooms->remark_stock = null;
            $rooms->out = 0;

            $rooms->save();

            Log::addLog($request->user_id, json_encode($rooms_old), 'Restore Room : ' . $rooms);
        }

        Alert::success('Success', 'อัพเดทข้อมูลสำเร็จ!');
        return redirect()->back();
    }

    public function approve(Request $request)
    {
        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $dataLoginUser = Session::get('loginId');

        //permission sub by dept
        $isRole = Role_user::where('user_id', Session::get('loginId')['user_id'])->first();
        //dd($isRole);
        //$projects = Project::orderBy('name', 'asc')->where('active', 1)->get();

        $projects = Room::with('project')
            ->where('status_id', 0)
            ->get()
            ->groupBy('project_id')
            ->map(function ($item) {
                // Check if the project is not null before calling the only method
                if ($item->first()->project !== null) {
                    return $item->first()->project->only(['id', 'name']);
                }
            })
            ->filter() // This will remove any null values from the map
            ->values();


       // $rooms = Room::with(['project', 'plan', 'user', 'status', 'booking'])->where('status_id', 0);
        $rooms = Room::with(['project', 'plan','status', 'booking'])->where('status_id', 0);

        // dd($rooms);
        $rooms = $rooms->orderBy('room_address', 'asc')->get();
        $roomsCount = $rooms->count();

        if ($isRole->dept == 'Finance' || $isRole->role_type == 'SuperAdmin') {
            return view("rooms.finance.room_approve", compact(
                'dataLoginUser',
                'projects',
                'rooms',
                'roomsCount',
                'isRole'
            ));
        } else {
            return redirect(route("main"));
        }
    }
    public function update_approve(Request $request)
    {
        $dataLoginUser = Session::get('loginId');
        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        $user_id = $dataLoginUser['user_id'];
        $selectedApprove = $request->input('selected_rooms', []);


        // Fetch
        $approveRooms = Room::whereIn('id', $selectedApprove)->get();


        Room::whereIn('id', $selectedApprove)->update(['status_id' => 1]);


        // Log the deletion with  details
        foreach ($approveRooms as $room) {
            Log::addLog($user_id, '', 'approve Room - room_id: ' . $room->id . ', project_id: ' . $room->project_id);
        }

        return response()->json([
            'message' => 'อนุมัติห้องเรียบร้อย!',
        ], 201);
    }

    public function deleteRoom(Request $request, $id, $user_id)
    {

        $deletedRooms = Room::where('id', $id)->first();

        Log::addLog($user_id, '', 'Delete Room - room_id: ' . $deletedRooms->id . ', project_id: ' . $deletedRooms->project_id);
        $deletedRooms->delete();

        return response()->json([
            'message' => 'ลบข้อมูลสำเร็จ',
        ], 201);
    }

    public function searchApprove(Request $request)
    {
        //$dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $dataLoginUser = Session::get('loginId');
        //permission sub by dept
        $isRole = Role_user::where('user_id',Session::get('loginId')['user_id'])->first();
        //dd($isRole);
        $projects = Room::with('project')
            ->where('status_id', 0)
            ->get()
            ->groupBy('project_id')
            ->map(function ($item) {
                // Check if the project is not null before calling the only method
                if ($item->first()->project !== null) {
                    return $item->first()->project->only(['id', 'name']);
                }
            })
            ->filter() // This will remove any null values from the map
            ->values();
        // $rooms = Room::with(['project', 'plan', 'user', 'status', 'booking'])->where('status_id', 0);

        //$rooms = Room::with(['project', 'plan', 'user', 'status', 'booking'])->where('project_id', $request->project_id)->where('status_id', 0);
        $rooms = Room::with(['project', 'plan', 'status', 'booking'])->where('project_id', $request->project_id)->where('status_id', 0);
        // dd($rooms);
        $rooms = $rooms->orderBy('room_address', 'asc')->get();
        $roomsCount = $rooms->count();

        if ($isRole->dept == 'Finance' || $isRole->role_type == 'SuperAdmin') {
            return view("rooms.finance.room_approve", compact(
                'dataLoginUser',
                'projects',
                'rooms',
                'roomsCount',
                'isRole'
            ));
        }
    }
}
