<?php

namespace App\Http\Controllers;

use App\Models\File_Price;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class SendMailContoller extends Controller
{

    public function fileExp()
    {
        $today = Carbon::now(); // วันที่ปัจจุบัน

        $next7Days = $today->copy()->addDays(7); // วันที่อีก 7 วัน
        $next15Days = $today->copy()->addDays(15); // วันที่อีก 15 วัน
        $next30Days = $today->copy()->addDays(30); // วันที่อีก 30 วัน

        $filesPrices = File_Price::leftJoin('projects', 'file_price.project_id', '=', 'projects.id')
            ->where('file_price.exp', '=', $next7Days->toDateString())
            ->orWhere('file_price.exp', '=', $next15Days->toDateString())
            ->orWhere('file_price.exp', '=', $next30Days->toDateString())
            ->orderBy('file_price.id', 'desc')
            ->select('file_price.*', 'projects.name as project_name')
            ->get();

        if ($filesPrices->isEmpty()) {
            return response()->json("ไม่พบรายการ", 200);
        } else {
            foreach ($filesPrices as $filePrice) {
                $daysUntilExpiry = $today->diffInDays($filePrice->exp); // คำนวณจำนวนวันจากวันปัจจุบันถึงวันหมดอายุ
                $daysUntilExpiry=$daysUntilExpiry+1;
                $project = $filePrice->project_name;

                $toEmail = $filePrice->email_alert ?? ['noreply@vbeyond.co.th'];
                $toBCC = ['noreply@vbeyond.co.th'];

                $data = [
                    'project' => $project,
                    'file_price' => $filePrice,
                    'days_before_expiry' => $daysUntilExpiry
                ];

                Mail::send(
                    'mail.fileexp',
                    ['data' => $data],
                    function (Message $message) use ($toEmail, $toBCC, $project) {
                        $message->to($toEmail)
                            ->bcc($toBCC)
                            ->subject("แจ้งเตือน ใบราคา/โปรโมชั่น โครงการ $project");
                        // ->attach($attachmentPath, $attachmentOptions);
                    }
                );
            }
            return response()->json($data, 200);
        }





    }


    //ส่งเมล์เมื่อไฟล์หมดอายุ
    // public function fileExp()
    // {

    //     $filesPrice = File_Price::leftJoin('projects', 'file_price.project_id', '=', 'projects.id')
    //         ->orderBy('file_price.id', 'desc')
    //         ->select('file_price.*', 'projects.name as project_name')
    //         ->first();
    //     //dd($filesPrice );
    //     if ($filesPrice) {
    //         $expDate = Carbon::parse($filesPrice->exp);
    //         $currentDate = Carbon::now();
    //         $project =$filesPrice->project_name;
    //         // ตรวจสอบแต่ละรอบของการแจ้งเตือน
    //         $daysBeforeExpiry = [7, 15, 30];

    //         foreach ($daysBeforeExpiry as $days) {
    //             // ตรวจสอบว่าใกล้หมดอายุหรือไม่
    //             if ($currentDate->diffInDays($expDate) <= $days) {
    //                 $toEmail = $filesPrice->email_alert ?? ['noreply@vbeyond.co.th'];
    //                 $toBCC = ['noreply@vbeyond.co.th'];

    //                 $data = [
    //                     'file_price' => $filesPrice,
    //                     'days_before_expiry' => $days
    //                 ];


    //                 // $attachmentPath = $filesPrice->file; // ปรับตามโครงสร้างที่คุณใช้
    //                 // $attachmentName = 'pricelist.pdf'; // ชื่อไฟล์ที่จะแสดงในอีเมล
    //                 // $attachmentOptions = [
    //                 //     'as' => $attachmentName,
    //                 //     'mime' => 'application/octet-stream', // ปรับตามประเภทของไฟล์
    //                 // ];

    //                 //return response()->json($data,200);

    //                 Mail::send(
    //                     'mail.fileexp',
    //                     ['data' => $data],
    //                     function (Message $message) use ($toEmail, $toBCC, $project) {
    //                         $message->to($toEmail)
    //                             ->bcc($toBCC)
    //                             ->subject("แจ้งเตือน ใบราคา/โปรโมชั่น โครงการ $project");
    //                             // ->attach($attachmentPath, $attachmentOptions);
    //                     }
    //                 );

    //             }
    //         }
    //     }
    // }
}
