// ดึงวันที่ปัจจุบัน
var currentDate = new Date();

// กำหนดวันที่เริ่มต้นเป็นวันที่ 1 ของเดือนปัจจุบัน
var startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

// กำหนดวันที่สิ้นสุดเป็นวันปัจจุบัน
var endDate = currentDate;

// Format the dates as "Y-m-d"
var formattedStartDate = startDate.toLocaleDateString('en-CA');
var formattedEndDate = endDate.toLocaleDateString('en-CA');


$('#startdate').val(formattedStartDate);
$('#enddate').val(formattedEndDate);

// เพิ่มการตรวจสอบเมื่อเปลี่ยนวันที่เริ่มต้น
$('#startdate').on('changeDate', function(e) {
    var selectedStartDate = e.date;
    $('#enddate').datepicker('setStartDate', selectedStartDate);
});
