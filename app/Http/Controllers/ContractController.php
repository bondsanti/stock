<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'room' => 'required',
            'deposit' => 'required',
        ], [
            'start_date.required' => 'กรอก วันที่เริ่มสัญญา',
            'end_date.required' => 'กรอก วันที่สิ้นสุดสัญญา',
            'room.required' => 'กรอก จำนวนห้อง',
            'deposit.required' => 'กรอก ค่ามัดจำ',
        ]);

        if ($validator->passes()) {

            $contractActive  = Contract::where('project_id', $request->project_id)->latest()->first();

            if ($contractActive) {
                $contractActive->is_active = "0";
                $contractActive->save();
            }

                $contract = new Contract();
                $contract->project_id = $request->project_id;
                $contract->room = $request->room;
                $contract->deposit = $request->deposit;
                $contract->amount = $request->deposit;
                $contract->start_date = $request->start_date;
                $contract->end_date = $request->end_date;
                $contract->note = $request->note;
                $contract->is_active = 1;
                $contract->save();

                // สร้าง log การ insert โดยใช้ addLog
                Log::addLog($request->user_id, '', 'Create Contact: ' . $contract);
                return response()->json(['message' => 'เพิ่มข้อมูลสำเร็จ','id'=>$request->project_id], 201);


        }else{
            return response()->json(['error' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id, $user_id)
    {
        $contract = Contract::where('id',$id)->first();

        if ($contract) {

            Log::addLog($user_id, '', 'Delete : ' . $contract);

            $contract->delete($id);


            return response()->json([
                'message' => 'ลบข้อมูลสำเร็จ',
                'id'=>$contract->project_id
            ], 201);

        }else{
            return response()->json([
                'message' => 'ไม่สามารถลบข้อมูลได้'
            ], 404);
        }
    }

    public function edit($id)
    {
        $contract = Contract::where('id',$id)->first();
        return response()->json($contract, 200);
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::where('id', $id)->first();
        $contract_old = $contract->toArray();

        $validator = Validator::make($request->all(), [
            'start_date_edit' => 'required',
            'end_date_edit' => 'required',
            'room_edit' => 'required',
            'deposit_edit' => 'required',
        ], [
            'start_date_edit.required' => 'กรอก วันที่เริ่มสัญญา',
            'end_date_edit.required' => 'กรอก วันที่สิ้นสุดสัญญา',
            'room_edit.required' => 'กรอก จำนวนห้อง',
            'deposit_edit.required' => 'กรอก ค่ามัดจำ',
        ]);

        if ($validator->passes()) {

            $contract->room = $request->room_edit;
            $contract->deposit = $request->deposit_edit;
            $contract->amount = $request->deposit_edit;
            $contract->start_date = $request->start_date_edit;
            $contract->end_date = $request->end_date_edit;
            $contract->note = $request->note_edit;

            if($contract->deposit != $request->deposit){

                $sum_deposit = DB::connection('mysql')->table('contract_log')->where('contract_id', $id)->sum('deposit') ;
                $contract->deposit = $request->deposit;
                $contract->amount = $request->deposit - $sum_deposit;
            }


            $contract->save();

            Log::addLog($request->user_id,json_encode($contract_old), 'Update Contract : ' . $contract);

            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ',
                'id' => $request->project_id
            ], 201);

            return redirect()->back();
        } else {
            return response()->json(['error' => $validator->errors()]);
        }


    }

    public function detail($id)
    {
        $contract_log =  DB::connection('mysql')->table('contract_log')->where('contract_id', $id)->get();

        return response()->json($contract_log, 200);


    }

    public function returndeposit(Request $request)
    {
        $contract = Contract::where('id',$request->contract_id)->first();

        $contract_old = $contract->toArray();

        $validator = Validator::make($request->all(), [
            'date_deposit' => 'required',
            'room_deposit' => 'required',
            'deposit' => 'required',
            'status' => 'required',
        ], [
            'date_deposit.required' => 'กรอก วันที่',
            'room_deposit.required' => 'กรอก จำนวนห้อง',
            'deposit.required' => 'กรอก ค่ามัดจำ',
            'status.required' => 'เลือก สถานะ',
        ]);
        if ($validator->passes()) {

            if($request->status == 1){
                $contract->amount = $contract->amount - $request->deposit;
                $contract->save();
            }

            DB::connection('mysql')->table('contract_log')->insert([
                'contract_id' => $contract->id,
                'project_id' => $contract->project_id,
                'status' => $request->status,
                'deposit' => $request->deposit,
                'note' => $request->note_deposit,
                'room_amount' => $request->room_deposit,
                'created_at' =>  $request->date_deposit,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            Log::addLog($request->user_id,json_encode($contract_old), 'Update Deposit : ' . $contract);
            $deposit = DB::connection('mysql')->table('contract_log')->latest()->first();
            Log::addLog($request->user_id,'', 'Create Deposit : ' . json_encode($deposit));

            return response()->json([
                'message' => 'อัพเดทข้อมูลสำเร็จ',
                'id' => $contract->project_id
            ], 201);

            return redirect()->back();
        }else{
            return response()->json(['error' => $validator->errors()]);
        }
    }

}
