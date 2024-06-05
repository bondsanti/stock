<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ส่ง Email</title>
</head>
<body>
    <h2>เรียนผู้เกี่ยวข้อง</h2>
    <h4>มีการนำเข้าห้องชุดใหม่ !!
        <br>โครงการ <font color="red"> {{$project}} </font> ทั้งหมด <font color="red">{{$roomsCount}}</font> ห้อง
        <br>จากระบบ Stock </h4>
    <h4>กรุณาตรวจสอบความถูกต้อง และอนุมัติห้องชุดดังกล่าว</h4>
    <h2><a href="{{$Link}}" target="_blank">คลิ๊กเพื่ออนุมัติ</a></h2>
</body>
</html>
