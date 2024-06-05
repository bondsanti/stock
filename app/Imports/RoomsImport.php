<?php

namespace App\Imports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class RoomsImport implements ToModel, WithHeadingRow, WithValidation, WithCalculatedFormulas
{
    use Importable;

    public function model(array $row)
    {

        $roomAddress = $row['room_address'];
        $project = $row['project_id'];

        // Check ซ้ำ ถ้าข้อมูล ซ้ำจะไม่ลงฐานข้อมูล
        if (!Room::where('project_id', $project)->where('room_address', $roomAddress)->exists()) {
            $fixseller = $row['fixseller'] ?? '';

           return new Room([
                'project_id'        => $row['project_id'],
                'plan_id'           => $row['plan_id'],
                'status_id'         => $row['status_id'],
                'floor'             => $row['floor'],
                'room_address'      => $row['room_address'],
                'address'           => $row['address'],
                'price'             => $row['price'],
                'area'              => round($row['area'], 2),
                'building'          => $row['building'],
                'sqm_price'         => round($row['price'] / $row['area']),
                'direction'         => $row['direction'],
                'fixseller'         => $fixseller,
                'special_price1'    => $row['special_price1'],
                'special_price2'    => $row['special_price2'],
                'special_price3'    => $row['special_price3'],
            ]);

            // $room->save();
        }

        return null;

    }


    public function headingRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        return [
            'project_id'    => 'required',
            'plan_id'       => 'required',
            'status_id'     => 'required',
            'price'         => 'required',
            'area'          => 'required',
            'room_address'  => 'required',

        ];
    }
    public function customValidationMessages()
    {
        return [
            'project_id.required'   => "กรุณากรอก project_id",
            'plan_id.required'      => "กรุณากรอก plan_id",
            'status_id.required'    => "กรุณากรอก สถานะ ",
            'price.required'        => "กรุณากรอกprice",
            'area.required'         => "กรุณากรอก area",
            'room_address.required' => 'กรุณากรอก room_address',
        ];
    }
}
