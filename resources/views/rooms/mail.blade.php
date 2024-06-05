<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ส่ง Email</title>
</head>
<body>
    <h2>แจ้งเตือน</h2>
    <h4>การจองของคุณถูกกยกเลิก
        <br>โครงการ <font color="red"> {{$project}} </font> ห้องเลขที่ <font color="red">{{$roomaddress}}</font>
        <br>ลูกค้าชื่อ <font color="blue"> {{$customer}} </font></h4>
    <h4>เหตุผล : {{$because}}</h4>

</body>
</html>
