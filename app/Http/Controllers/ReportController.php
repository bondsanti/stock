<?php

namespace App\Http\Controllers;

use App\Imports\RoomsImport;
use App\Models\Facility;
use App\Models\Furniture;
use App\Models\Log;
use App\Models\Plan;
use App\Models\Room;
use App\Models\Project;
use App\Models\Promotion;
use App\Models\Role_user;
use App\Models\Status_Room;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();

        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get()->filter(function ($value) use ($dataLoginUser) {
            if ($dataLoginUser->high_rise == 1 && $dataLoginUser->low_rise == 1) {
                return true;
            }
            if ($dataLoginUser->high_rise == 1 && $value->project->high_rise == 1) {
                return true;
            }
            if ($dataLoginUser->low_rise == 1 && $value->project->low_rise == 1) {
                return true;
            }
            return false;
        });


        $projects_data = DB::select("
        SELECT
            projects.id,
            projects.name,
            projects.active,
            contracts.amount,
            contracts.deposit,
            contracts.is_active,
            contracts.start_date,
            contracts.end_date,
            COUNT(CASE WHEN rooms.status_id = 2 THEN 1 ELSE NULL END) AS booking,
            COUNT(CASE WHEN rooms.status_id != 5 THEN 1 ELSE NULL END) AS all_rooms,
            COUNT(CASE WHEN rooms.status_id = 2 AND rooms.sladay > 0 THEN 1 ELSE NULL END) AS slabooking,
            COUNT(CASE WHEN rooms.status_id = 1 THEN 1 ELSE NULL END) AS available,
            COUNT(CASE WHEN rooms.status_id = 3 THEN 1 ELSE NULL END) AS contract,
            COUNT(CASE WHEN rooms.status_id = 6 THEN 1 ELSE NULL END) AS notready,
            COUNT(CASE WHEN rooms.status_id = 3 AND rooms.sladay > 0 THEN 1 ELSE NULL END) AS slacontract,
            COUNT(CASE WHEN rooms.status_id = 4 THEN 1 ELSE NULL END) AS mortgage,
            COUNT(CASE WHEN rooms.status_id = 5 THEN 1 ELSE NULL END) AS waiting,
            SUM(CASE WHEN rooms.status_id = 4 THEN rooms.price ELSE 0 END) AS mortgage_price,
            SUM(CASE WHEN rooms.status_id = 3 THEN rooms.price ELSE 0 END) AS contract_price,
            SUM(CASE WHEN rooms.status_id = 2 THEN rooms.price ELSE 0 END) AS booking_price
        FROM projects
        LEFT JOIN contracts ON contracts.project_id = projects.id
        LEFT JOIN rooms ON rooms.project_id = projects.id
        WHERE projects.active = 1 AND (contracts.is_active = 1 OR contracts.is_active IS NULL)
        GROUP BY projects.id, projects.name, projects.active, contracts.amount, contracts.deposit, contracts.is_active, contracts.start_date, contracts.end_date
        ORDER BY projects.name ASC
        ");

        $data = [];

        foreach ($projects_data as $project) {
            $price_all = $project->booking_price + $project->contract_price + $project->mortgage_price;

            array_push($data, [
                'project' => $project,
                'price_all' => $price_all,
            ]);
        }

        $roomData = Room::whereIn('out', [0, 1])
            ->whereHas('project', function ($query) {
                $query->where('active', 1);
            })
            ->select('status_id', 'out', 'project_id')
            ->with('project')
            ->get();


        $roomStatuses = [
            'IN' => [],
            'OUT' => [],
        ];

        foreach ($roomData as $room) {
            $roomStatuses[$room->out === 0 ? 'IN' : 'OUT'][] = $room->status_id;
        }

        $pro_in_mortgage = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 4));
        $pro_in_waiting = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 2));
        $pro_in_contract = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 3));
        $pro_in_available = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 1));
        $pro_in_notready = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 6));
        $pro_in_all = count($roomStatuses['IN']);

        $pro_out_mortgage = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 4));
        $pro_out_waiting = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 2));
        $pro_out_contract = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 3));
        $pro_out_available = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 1));
        $pro_out_notready = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 6));
        $pro_out_all = count($roomStatuses['OUT']);

        //return response()->json($data,200);
        return view('reports.index', compact(
            'data',
            'projects',
            'dataLoginUser',
            'pro_in_mortgage',
            'pro_in_waiting',
            'pro_in_contract',
            'pro_in_available',
            'pro_in_notready',
            'pro_in_all',
            'pro_out_mortgage',
            'pro_out_waiting',
            'pro_out_contract',
            'pro_out_available',
            'pro_out_notready',
            'pro_out_all',
            'isRole'
        ));
    }


    public function search(Request $request)
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();

        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get()->filter(function ($value) use ($dataLoginUser) {
            if ($dataLoginUser->high_rise == 1 && $dataLoginUser->low_rise == 1) {
                return true;
            }
            if ($dataLoginUser->high_rise == 1 && $value->project->high_rise == 1) {
                return true;
            }
            if ($dataLoginUser->low_rise == 1 && $value->project->low_rise == 1) {
                return true;
            }
            return false;
        });

        $projects_data = Project::selectRaw('
    projects.id,
    projects.name,
    projects.active,
    contracts.amount,
    contracts.deposit,
    contracts.is_active,
    contracts.start_date,
    contracts.end_date,
    COUNT(CASE WHEN rooms.status_id = 2 THEN 1 ELSE NULL END) AS booking,
    COUNT(CASE WHEN rooms.status_id != 5 THEN 1 ELSE NULL END) AS all_rooms,
    COUNT(CASE WHEN rooms.status_id = 2 AND rooms.sladay > 0 THEN 1 ELSE NULL END) AS slabooking,
    COUNT(CASE WHEN rooms.status_id = 1 THEN 1 ELSE NULL END) AS available,
    COUNT(CASE WHEN rooms.status_id = 3 THEN 1 ELSE NULL END) AS contract,
    COUNT(CASE WHEN rooms.status_id = 6 THEN 1 ELSE NULL END) AS notready,
    COUNT(CASE WHEN rooms.status_id = 3 AND rooms.sladay > 0 THEN 1 ELSE NULL END) AS slacontract,
    COUNT(CASE WHEN rooms.status_id = 4 THEN 1 ELSE NULL END) AS mortgage,
    COUNT(CASE WHEN rooms.status_id = 5 THEN 1 ELSE NULL END) AS waiting,
    SUM(CASE WHEN rooms.status_id = 4 THEN rooms.price ELSE 0 END) AS mortgage_price,
    SUM(CASE WHEN rooms.status_id = 3 THEN rooms.price ELSE 0 END) AS contract_price,
    SUM(CASE WHEN rooms.status_id = 2 THEN rooms.price ELSE 0 END) AS booking_price
')
            ->leftJoin('contracts', 'contracts.project_id', '=', 'projects.id')
            ->leftJoin('rooms', 'rooms.project_id', '=', 'projects.id')
            ->where('projects.active', 1)
            ->where(function ($query) {
                $query->where('contracts.is_active', 1)->orWhereNull('contracts.is_active');
            });

        if ($request->project_id > 0) {
            $projects_data->where('rooms.project_id', '=', $request->input('project_id'));
        }

        if ($request->start_date) {
            $startTimestamp = strtotime($request->start_date);
            $projects_data->where('rooms.updated_at', '>=', date('Y-m-d H:i:s', $startTimestamp));
        }

        if ($request->end_date) {
            $endTimestamp = strtotime($request->end_date);
            $projects_data->where('rooms.updated_at', '<=', date('Y-m-d H:i:s', $endTimestamp));
        }

        if ($request->type) {
            if ($request->type == 'all') {
                // ไม่ต้องเพิ่มเงื่อนไขใด ๆ หากเป็น "all"
            } elseif ($request->type == 'out') {
                $projects_data->where('rooms.out','=', '1');
            } elseif ($request->type == 'in') {
                $projects_data->where('rooms.out','=' ,'0');
            }
        }



        $projects_data = $projects_data
            ->groupBy(
                'projects.id',
                'projects.name',
                'projects.active',
                'contracts.amount',
                'contracts.deposit',
                'contracts.is_active',
                'contracts.start_date',
                'contracts.end_date'
            )
            ->orderBy('projects.name', 'ASC')
            ->get();

        $data = [];

        foreach ($projects_data as $project) {
            $price_all = $project->booking_price + $project->contract_price + $project->mortgage_price;

            array_push($data, [
                'project' => $project,
                'price_all' => $price_all,
            ]);
        }








        $roomData = Room::whereIn('rooms.out', [0, 1])
            ->whereHas('project', function ($query) {
                $query->where('active', 1);
            })
            ->select('status_id', 'out', 'project_id')
            ->with('project');
        if ($request->project_id > 0) {
            $roomData->where('rooms.project_id', '=',$request->project_id);
        }
        if ($request->start_date) {
            $startTimestamp = strtotime($request->start_date);
            $roomData->where('rooms.updated_at', '>=', date('Y-m-d H:i:s', $startTimestamp));
        }

        if ($request->end_date) {
            $endTimestamp = strtotime($request->end_date);
            $roomData->where('rooms.updated_at', '<=', date('Y-m-d H:i:s', $endTimestamp));
        }


        $roomData = $roomData->get();



        $roomStatuses = [
            'IN' => [],
            'OUT' => [],
        ];

        foreach ($roomData as $room) {
            $roomStatuses[$room->out === 0 ? 'IN' : 'OUT'][] = $room->status_id;
        }

        $pro_in_mortgage = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 4));
        $pro_in_waiting = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 2));
        $pro_in_contract = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 3));
        $pro_in_available = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 1));
        $pro_in_notready = count(array_filter($roomStatuses['IN'], fn ($status) => $status === 6));
        $pro_in_all = count($roomStatuses['IN']);

        $pro_out_mortgage = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 4));
        $pro_out_waiting = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 2));
        $pro_out_contract = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 3));
        $pro_out_available = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 1));
        $pro_out_notready = count(array_filter($roomStatuses['OUT'], fn ($status) => $status === 6));
        $pro_out_all = count($roomStatuses['OUT']);

        $formInputs = $request->all();
       // return response()->json($data, 200);
        return view('reports.search', compact(
            'data',
            'formInputs',
            'projects',
            'dataLoginUser',
            'pro_in_mortgage',
            'pro_in_waiting',
            'pro_in_contract',
            'pro_in_available',
            'pro_in_notready',
            'pro_in_all',
            'pro_out_mortgage',
            'pro_out_waiting',
            'pro_out_contract',
            'pro_out_available',
            'pro_out_notready',
            'pro_out_all',
            'isRole'
        ));
    }


    public function searchIn()
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();

        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get()->filter(function ($value) use ($dataLoginUser) {
            if ($dataLoginUser->high_rise == 1 && $dataLoginUser->low_rise == 1) {
                return true;
            }
            if ($dataLoginUser->high_rise == 1 && $value->project->high_rise == 1) {
                return true;
            }
            if ($dataLoginUser->low_rise == 1 && $value->project->low_rise == 1) {
                return true;
            }
            return false;
        });


        $projects_data = DB::select("
        SELECT
            projects.id,
            projects.name,
            projects.active,
            contracts.amount,
            contracts.deposit,
            contracts.is_active,
            contracts.start_date,
            contracts.end_date,
            COUNT(CASE WHEN rooms.status_id = 2 AND rooms.out = 0 THEN 1 ELSE NULL END) AS booking,
            COUNT(CASE WHEN rooms.status_id != 5 AND rooms.out = 0 THEN 1 ELSE NULL END) AS all_rooms,
            COUNT(CASE WHEN rooms.status_id = 2 AND rooms.sladay > 0 AND rooms.out = 0 THEN 1 ELSE NULL END) AS slabooking,
            COUNT(CASE WHEN rooms.status_id = 1 AND rooms.out = 0 THEN 1 ELSE NULL END) AS available,
            COUNT(CASE WHEN rooms.status_id = 3 AND rooms.out = 0 THEN 1 ELSE NULL END) AS contract,
            COUNT(CASE WHEN rooms.status_id = 6 AND rooms.out = 0 THEN 1 ELSE NULL END) AS notready,
            COUNT(CASE WHEN rooms.status_id = 3 AND rooms.sladay > 0 AND rooms.out = 0 THEN 1 ELSE NULL END) AS slacontract,
            COUNT(CASE WHEN rooms.status_id = 4 AND rooms.out = 0 THEN 1 ELSE NULL END) AS mortgage,
            COUNT(CASE WHEN rooms.status_id = 5 AND rooms.out = 0 THEN 1 ELSE NULL END) AS waiting,
            SUM(CASE WHEN rooms.status_id = 4 AND rooms.out = 0 THEN rooms.price ELSE 0 END) AS mortgage_price,
            SUM(CASE WHEN rooms.status_id = 3 AND rooms.out = 0 THEN rooms.price ELSE 0 END) AS contract_price,
            SUM(CASE WHEN rooms.status_id = 2 AND rooms.out = 0 THEN rooms.price ELSE 0 END) AS booking_price

        FROM projects
        LEFT JOIN contracts ON contracts.project_id = projects.id
        LEFT JOIN rooms ON rooms.project_id = projects.id
        WHERE projects.active = 1 AND (contracts.is_active = 1 OR contracts.is_active IS NULL)
        GROUP BY projects.id, projects.name, projects.active, contracts.amount, contracts.deposit, contracts.is_active, contracts.start_date, contracts.end_date
        ORDER BY projects.name ASC
        ");

        $data = [];

        foreach ($projects_data as $project) {
            $price_all = $project->booking_price + $project->contract_price + $project->mortgage_price;

            array_push($data, [
                'project' => $project,
                'price_all' => $price_all,
            ]);
        }
        //return response()->json($data,200);
        return view('reports.in', compact('data', 'projects', 'dataLoginUser','isRole'));
    }

    public function searchOut()
    {

        $dataLoginUser = User::with('role_position:id,name')->where('id', Session::get('loginId'))->first();
        // dd($dataLoginUser);
        $isRole = Role_user::where('user_id', Session::get('loginId'))->first();

        $projects = Project::orderBy('name', 'asc')->where('active', 1)->get()->filter(function ($value) use ($dataLoginUser) {
            if ($dataLoginUser->high_rise == 1 && $dataLoginUser->low_rise == 1) {
                return true;
            }
            if ($dataLoginUser->high_rise == 1 && $value->project->high_rise == 1) {
                return true;
            }
            if ($dataLoginUser->low_rise == 1 && $value->project->low_rise == 1) {
                return true;
            }
            return false;
        });


        $projects_data = DB::select("
        SELECT
            projects.id,
            projects.name,
            projects.active,
            contracts.amount,
            contracts.deposit,
            contracts.is_active,
            contracts.start_date,
            contracts.end_date,
            COUNT(CASE WHEN rooms.status_id = 2 AND rooms.out = 1 THEN 1 ELSE NULL END) AS booking,
            COUNT(CASE WHEN rooms.status_id != 5 AND rooms.out = 1 THEN 1 ELSE NULL END) AS all_rooms,
            COUNT(CASE WHEN rooms.status_id = 2 AND rooms.sladay > 0 AND rooms.out = 1 THEN 1 ELSE NULL END) AS slabooking,
            COUNT(CASE WHEN rooms.status_id = 1 AND rooms.out = 1 THEN 1 ELSE NULL END) AS available,
            COUNT(CASE WHEN rooms.status_id = 3 AND rooms.out = 1 THEN 1 ELSE NULL END) AS contract,
            COUNT(CASE WHEN rooms.status_id = 6 AND rooms.out = 1 THEN 1 ELSE NULL END) AS notready,
            COUNT(CASE WHEN rooms.status_id = 3 AND rooms.sladay > 0 AND rooms.out =1 THEN 1 ELSE NULL END) AS slacontract,
            COUNT(CASE WHEN rooms.status_id = 4 AND rooms.out = 1 THEN 1 ELSE NULL END) AS mortgage,
            COUNT(CASE WHEN rooms.status_id = 5 AND rooms.out = 1 THEN 1 ELSE NULL END) AS waiting,
            SUM(CASE WHEN rooms.status_id = 4 AND rooms.out = 1 THEN rooms.price ELSE 0 END) AS mortgage_price,
            SUM(CASE WHEN rooms.status_id = 3 AND rooms.out = 1 THEN rooms.price ELSE 0 END) AS contract_price,
            SUM(CASE WHEN rooms.status_id = 2 AND rooms.out = 1 THEN rooms.price ELSE 0 END) AS booking_price

        FROM projects
        LEFT JOIN contracts ON contracts.project_id = projects.id
        LEFT JOIN rooms ON rooms.project_id = projects.id
        WHERE projects.active = 1 AND (contracts.is_active = 1 OR contracts.is_active IS NULL)
        GROUP BY projects.id, projects.name, projects.active, contracts.amount, contracts.deposit, contracts.is_active, contracts.start_date, contracts.end_date
        ORDER BY projects.name ASC
        ");

        $data = [];

        foreach ($projects_data as $project) {
            $price_all = $project->booking_price + $project->contract_price + $project->mortgage_price;

            array_push($data, [
                'project' => $project,
                'price_all' => $price_all,
            ]);
        }

        //return response()->json($data,200);
        return view('reports.out', compact('data', 'projects', 'dataLoginUser','isRole'));
    }
}
