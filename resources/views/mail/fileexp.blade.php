<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ส่ง Email</title>
</head>
<body>
    <h2>แจ้งเตือน !! อีก {{ $data['days_before_expiry'] }} วัน <h2>
    <h3>ใบราคา/โปรโมชั่น โครงการ : <font color="red">{{ $data['file_price']['project_name'] }}</font> </h3>
    <h3>ไฟล์ดังกล่าว จะหมดอายุภายในวันที่ : <font color="red">{{ $data['file_price']['exp'] }}</font></h3>
    <a href="https://property.vbeyond.co.th" target="_blank"><h3>By Stock system</h3></a>
</body>

</html>
