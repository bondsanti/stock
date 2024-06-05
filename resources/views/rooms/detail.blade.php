@extends('layouts.app')

@section('title', 'ห้อง')

@section('content')
<form id="editForm" name="editForm"  method="post" action="{{route('room.update')}}">
    @csrf
    <input type="hidden" name="user_id" value="{{$dataLoginUser->id}}">
    <input type="hidden" name="room_id" value="{{$rooms->id}}">
    <div class="content-header">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-9">

                    <h1 class="m-0">
                        ห้อง {{ $rooms->room_address }} ( {{ $rooms->address }} )

                        {{-- <a href="{{route('room')}}" class="btn bg-gradient-primary " type="button">
                            <i class="fa-solid fa fa-reply"></i> กลับ </a> --}}
                            <a href="javascript:void(0);" class="btn bg-gradient-primary " type="button" onclick="goBack();">
                                <i class="fa-solid fa fa-reply"></i> กลับ </a>
                            {{-- @if ($message!="")
                            <a href="" class="btn btn-danger col-4">
                                <i class="icon fas fa-ban"></i>Alert !! {{$message}}</a>
                            @endif --}}



                    </h1>


                </div><!-- /.col -->


            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-9">

                    <div class="card">
                        <div class="card-header">
                            <div class="panel-heading">
                                <h4 class="panel-title">ข้อมูลห้อง</h4>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="project">โครงการ</label>
                                    <select class="form-control " id="projectSelect" name="project_id" readonly>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ $project->id == $rooms->project_id ? 'selected' : '' }}>
                                                {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="project">plan</label>
                                    <select class="form-control " id="planSelect" name="plan_id" readonly>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="project">Type</label>

                                    <select name="type_id" class="form-control" readonly>
                                        <option value="1" {{ $rooms->room_type == 1 ? 'selected' : '' }}>Condo
                                        </option>
                                        <option value="2" {{ $rooms->room_type == 2 ? 'selected' : '' }}>บ้านเดี่ยว
                                        </option>
                                        <option value="3" {{ $rooms->room_type == 3 ? 'selected' : '' }}>ทาวน์โฮม
                                        </option>
                                        <option value="4" {{ $rooms->room_type == 4 ? 'selected' : '' }}>Duplex
                                        </option>
                                        <option value="5" {{ $rooms->room_type == 5 ? 'selected' : '' }}>Shop House
                                        </option>
                                        <option value="6" {{ $rooms->room_type == 6 ? 'selected' : '' }}>PentHouse
                                        </option>
                                    </select>
                                </div>
                            </div><!--form-group row -->
                            <div class="form-group row ">
                                <div class="col-md-4">
                                    <label for="">ชั้น</label>
                                    <input class="form-control" name="floor" type="number" value="{{ $rooms->floor }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">อาคาร</label>
                                    <input type="text" class="form-control" name="building"
                                        value="{{ $rooms->building }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">ห้องเลขที่</label>
                                    <input class="form-control" name="room_address" type="text"
                                        value="{{ $rooms->room_address }}" readonly>
                                </div>
                            </div><!-- form-group row -->
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="">บ้านเลขที่</label>
                                    <input class="form-control" name="address" type="text"
                                        value="{{ $rooms->address }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">ทิศ / ฝั่ง</label>
                                    <input type="text" class="form-control" name="direction"
                                        value="{{ $rooms->direction }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">กุญแจ หน้า / นอน / เมล์บลอค (จำนวน)</label>
                                    <input type="number" class="form-control" name="key" value="{{ $rooms->key }}" readonly>
                                </div>
                            </div><!-- form-group row2 -->
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="">คีย์การ์ด P/B (จำนวน)</label>
                                    <input type="text" class="form-control" name="keycard"
                                        value="{{ $rooms->keycard }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">วันจอง</label>
                                    <input type="text" class="form-control datepicker" name="booking_date"
                                        value="{{ $rooms->booking_date }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">วันทำสัญญา</label>
                                    <input type="text" class="form-control datepicker" name="contract_date"
                                        value="{{ $rooms->contract_date }}" readonly>
                                </div>
                            </div>

                        </div><!--card-header -->
                    </div><!--card-->

                    <!-- สถานะ โอน Status=4 -->
                    <div class="card show-mortgage" style="display: none;">
                        <div class="card-header">
                            <div class="panel-heading">
                                <h4 class="panel-title">รายละเอียดการโอน</h4>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="">วันที่โอน</label>
                                    <input class="form-control datepicker" name="mortgaged_date" type="text"
                                        value="{{ $rooms->mortgaged_date }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">วันที่รับมอบห้อง</label>
                                    <input type="text" class="form-control datepicker" name="receive_room_date"
                                        value="{{ $rooms->receive_room_date }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="">ลักษณะอาศัย</label>
                                    <select name="type_living" id="type_living" class="form-control" readonly>
                                        <option value="">เลือก</option>
                                        <option value="1">อยู่เอง</option>
                                        <option value="2">การันตี</option>
                                    </select>
                                </div>
                            </div><!--form-group row -->

                        </div><!--card-header -->
                    </div><!--card-->

                    <!-- การันตี-->
                    <div class="card show-guarantee" style="display: none;">
                        <div class="card-header">
                            <div class="panel-heading">
                                <h4 class="panel-title">การันตี</h4>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="project">ระยะเริ่มการันตี</label>
                                    <input class="form-control datepicker" name="guarantee_start" id="guarantee_start" type="text" value="{{ $rooms->guarantee_start }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="project">ระยะสิ้นสุดการันตี</label>
                                    <input class="form-control datepicker" name="guarantee_end" id="guarantee_end" type="text" value="{{ $rooms->guarantee_end }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="project">จำนวนเงินการันตี</label>
                                    <input class="form-control" name="guarantee_price" type="number" value="{{ $rooms->guarantee_price }}" readonly>
                                </div>
                            </div><!--form-group row -->

                        </div><!--card-header -->
                    </div><!--card-->


                    <!-- ลูกค้า -->
                    {{-- <div class="card">
                        <div class="card-body">
                            <h4>ลูกค้า </h4>
                            @if ($rooms->status_id==1)
                            <div class="row">
                                <div class="col-6">
                                    <input type="checkbox" name="outsource" id="outsource" value="1">

                                    <label for="1">ลูกค้า ไม่อยู่ในระบบ (วิเคราะห์)</label>
                                </div>
                                <div class="col-6"></div>
                            </div>
                            @endif
                            <div class="row">
                                @if ($rooms->status_id==1)
                                <div class="col-4" id="cusdb">

                                    <label for="project">เลือกลูกค้า</label>
                                    <select class="form-control select2" style="width: 100%;" id="customer_id" name="customer_id">
                                        <option value=""> เลือกลูกค้า</option>
                                    </select>

                                </div>
                                @endif
                                <div class="col-4"   @if ($rooms->status_id == 1) id="cusadd" style="display: none;" @endif>
                                    <label for="">ชื่อลูกค้า</label>
                                    <input class="form-control text-danger" name="owner" type="text" value="{{ $rooms->owner }}">
                                </div>
                                <div class="col-2"  @if ($rooms->status_id == 1) id="cusadd" style="display: none;" @endif>
                                    <label for="">เบอร์โทรศัพท์ลูกค้า</label>
                                    <input class="form-control text-danger" name="phone" type="text" value="{{ $rooms->phone }}">
                                </div>
                                <div class="col-2" @if ($rooms->status_id == 1) id="cusadd" style="display: none;" @endif>
                                    <label for="">สายงาน</label>
                                    <select name="workfield" class="form-control">
                                        <option value="1" {{ ( $rooms->workfield == 1) ? 'selected' : '' }}>Sale-In</option>
                                        <option value="2" {{ ( $rooms->workfield == 2) ? 'selected' : '' }}>Outsource</option>
                                        <option value="3" {{ ( $rooms->workfield == 3) ? 'selected' : '' }}>Walk-In</option>
                                    </select>
                                </div>
                                <div class="col-2" @if ($rooms->status_id == 1) id="cusadd" style="display: none;" @endif>
                                    <label for="">Team</label>
                                    <input class="form-control text-danger" name="agent" type="text" value="{{ $rooms->agent }}">
                                </div>
                                <div class="col-2" @if ($rooms->status_id == 1) id="cusadd" style="display: none;" @endif>
                                    <label for="">เบอร์โทรศัพท์สายงาน</label>
                                    <input class="form-control text-danger" name="agent_phone" type="text" value="{{ $rooms->agent_phone }}">
                                </div>

                            </div>


                        </div>
                    </div> --}}

                    <!-- เครื่องใช้ไฟฟ้า-->
                    <div class="card">
                        <div class="card-body">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">

                                    <h4 class="panel-title">เครื่องใช้ไฟฟ้า</h4>
                                </div>
                                <div class="panel-body">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                @foreach ($facility as $item)
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="facility[]"
                                                            value="{{ $item->id }}"
                                                            @foreach ($rooms->facilities as $key => $value) {{ $value->id == $item->id ? 'checked' : '' }} @endforeach disabled>

                                                        <label for="{{ $item->id }}">{{ $item->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- เฟอร์นิเจอร์-->
                    <div class="card">
                        <div class="card-body">
                            <div class="panel panel-inverse">
                                <div class="panel-heading">
                                    <h4 class="panel-title">เฟอร์นิเจอร์</h4>
                                </div>
                                <div class="panel-body">
                                    <div class=" row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                @foreach ($furniture as $item)
                                                    <div class="col-md-3">
                                                        <input type="checkbox" name="furniture[]"
                                                            value="{{ $item->id }}"
                                                            @foreach ($rooms->furnitures as $key => $value) {{ $value->id == $item->id ? 'checked' : '' }} @endforeach disabled>

                                                        <label for="{{ $item->id }}">{{ $item->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (($isRole->role_type=="SuperAdmin") || $isRole->dept=="Finance")
                    <!-- ราคาตัดทุน-->
                    <div class="card">
                        <div class="card-header">
                            <div class="panel-heading">
                                <h4 class="panel-title">ราคาตัดทุน</h4>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="project">ราคาตัดทุน 1</label>
                                    <input class="form-control" name="special_price1" type="number" value="{{ $rooms->special_price1 }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="project">ราคาตัดทุน 2</label>
                                    <input class="form-control" name="special_price2" type="number" value="{{ $rooms->special_price2 }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="project">ราคาตัดทุน 3</label>
                                    <input class="form-control" name="special_price3" type="number" value="{{ $rooms->special_price3 }}">
                                </div>
                            </div><!--form-group row -->
                            <div class="form-group row ">
                                <div class="col-md-4">
                                    <label for="project">ราคาตัดทุน 4</label>
                                    <input class="form-control" name="special_price4" type="number" value="{{ $rooms->special_price4 }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="project">ราคาตัดทุน 5</label>
                                    <input class="form-control" name="special_price5" type="number" value="{{ $rooms->special_price5 }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="project">ทีมสำหรับตัดทุน</label>
                                    <input class="form-control" name="special_price_team" type="number" value="{{ $rooms->special_price_team }}">
                                </div>
                            </div><!-- form-group row -->

                        </div><!--card-header -->
                    </div><!--card-->
                    @endif
                </div>
                <!-- สถานะ -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="status_id">สถานะ</label>
                                    <select class="form-control " id="status_id" name="status_id" readonly>
                                        @foreach ($status as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $rooms->status_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="promotion_id">โปรโมชั่น</label>
                                    <select class="form-control " id="promotion_id" name="promotion_id" readonly>
                                        <option value=""> เลือก </option>
                                        @foreach ($promotions as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $rooms->promotion_id ? 'selected' : '' }}>
                                                {{ $item->title }} </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="price">ราคาห้อง (บาท)</label>
                                    <input class="form-control" name="price" type="number"
                                        value="{{ $rooms->price }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="area">ขนาด (ตร.ม.)</label>
                                    <input type="text" class="form-control" name="area"
                                        value="{{ $rooms->area }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="using_area">พื้นที่ใช้สอย (ตร.ม.)</label>
                                    <input class="form-control" name="using_area" type="text"
                                        value="{{ $rooms->area }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="sqm_price">ราคาต่อ ตร.ม (บาท)</label>
                                    <input class="form-control" name="sqm_price" type="text"
                                        value="{{ $rooms->sqm_price }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="meter_code">รหัส มิเตอร์</label>
                                    <input class="form-control" name="meter_code" type="text"
                                        value="{{ $rooms->meter_code }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="electric_contract">electric contract</label>
                                    <input class="form-control" name="electric_contract" type="text"
                                        value="{{ $rooms->electric_contract }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="staffproj">ผู้ดูแล</label>
                                    <input class="form-control" name="staffproj" type="text"
                                        value="{{ $rooms->staffproj }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="fixseller">ช่องทางการขาย</label>
                                    <input class="form-control" name="fixseller" type="text"
                                        value="{{ $rooms->fixseller }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="remarkshow">Remark Show</label>
                                    <input class="form-control" name="remarkshow" type="text"
                                        value="{{ $rooms->remarkshow }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="remark">Remark</label>
                                    <textarea class="form-control" name="remark" cols="50" rows="7" readonly>{{ $rooms->remark }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- row -->
        </div><!-- /.container-fluid -->
    </section>
</form>
@endsection
@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>


    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var user_id = {{ $dataLoginUser->id }};
            var rooms_id = {{ $rooms->id }};

             //ยกเลิกการจอง
              $('body').on('click', '.data-cancel', function() {
                const id = $(this).data("id");

                Swal.fire({
                    title: 'คุณแน่ใจไหม? ',
                    text: "โปรดยืนยัน ยกเลิกการจอง",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: '/api/room/cancel/' + id + '/' + user_id,

                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'ยกเลิก การจองสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/rooms/edit') }}' + "/" + rooms_id;
                                }, 1500);
                                // setTimeout(
                                //     "location.href = '{{ route('room') }}';",
                                //     1500);

                            },
                            statusCode: {
                                400: function() {
                                    Swal.fire({
                                        position: 'top-center',
                                        icon: 'error',
                                        title: 'ไม่สามารถลบได้ เนื่องจาก ข้อมูลนี้มีใช้อยู่ในฐานข้อมูล',
                                        showConfirmButton: true,
                                        timer: 2500
                                    });
                                }
                            }
                        });
                    }
                });

            });

            var selectedProjectId = {{ $rooms->project_id }};

            loadPlans(selectedProjectId);

            $('#projectSelect').on('change', function() {
                var projectId = $(this).val();
                if (projectId) {
                    loadPlans(projectId);
                }
            });

            var initialPlanId = {{ $rooms->plan_id }};

            function loadPlans(projectId) {
                $.ajax({
                    url: '/getplans/' + projectId,
                    type: 'GET',
                    success: function(data) {
                        var plan = data.plans;
                        var options = '<option value="">เลือก Plan</option>';
                        for (let i = 0; i < plan.length; i++) {
                            var selected = (plan[i].id == initialPlanId) ? 'selected' : '';
                            options += '<option value="' + plan[i].id + '" ' + selected + '>' + plan[i]
                                .name + '</option>';
                        }
                        $('#planSelect').html(options);
                    }
                });
            }

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // รูปแบบวันที่
                autoclose: true,
            });

            $('#startdate').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#enddate').datepicker('setStartDate', selectedStartDate);
            });

            $('#guarantee_start').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#guarantee_end').datepicker('setStartDate', selectedStartDate);
            });

        });
    </script>
    <script>
       //type_living
        document.getElementById("type_living").addEventListener("change", function () {
            const selectedValue = this.value;

            if (selectedValue === "2") {
                document.querySelector(".show-guarantee").style.display = "block";
            } else {

                document.querySelector(".show-guarantee").style.display = "none";
            }
        });
    </script>

<script>
    //type_living
     document.getElementById("status_id").addEventListener("change", function () {
         const selectedValue = this.value;

         if (selectedValue === "4") {
             document.querySelector(".show-mortgage").style.display = "block";
         } else {

             document.querySelector(".show-mortgage").style.display = "none";
         }
     });
 </script>

    <script>

        document.getElementById('outsource').addEventListener('click', function() {
            const cusaddElements = document.querySelectorAll('#cusadd');
            const cusdbElement = document.getElementById('cusdb');

            // ตรวจสอบว่า checkbox ถูกเลือกหรือไม่
            if (this.checked) {
                // ถ้า checkbox ถูกเลือก ซ่อน cusdb และแสดง cusadd
                cusdbElement.style.display = 'none';
                cusaddElements.forEach(element => {
                    element.style.display = 'block';
                });
            } else {
                // ถ้า checkbox ไม่ถูกเลือก แสดง cusdb และซ่อน cusadd
                cusdbElement.style.display = 'block';
                cusaddElements.forEach(element => {
                    element.style.display = 'none';
                });
            }
        });
    </script>

    <script>
        // เมื่อหน้าเว็บโหลดเสร็จ
        document.addEventListener('DOMContentLoaded', function () {
            // ฟังก์ชันสำหรับโหลดข้อมูลลูกค้าทั้งหมด
            function loadAllCustomers() {
                // ใช้ AJAX หรือ Fetch API เพื่อดึงข้อมูลทั้งหมดจากเซิร์ฟเวอร์
                fetch(`/getcustomers`)
                    .then(response => response.json())
                    .then(data => {
                        const customerSelect = document.getElementById('customer_id');
                        // เคลียร์ตัวเลือกทั้งหมดใน select ก่อน
                        //customerSelect.innerHTML = '';

                        // เพิ่มข้อมูลลูกค้าลงใน select
                        data.forEach(customer => {
                            const option = new Option(customer.name, customer.pid);
                            customerSelect.append(option);
                        });

                        // เรียกใช้ select2 บน <select> ของคุณ
                        $('.select2').select2();
                    });
            }

            // โหลดข้อมูลลูกค้าทั้งหมดเมื่อหน้าเว็บโหลดเสร็จ
            loadAllCustomers();
        });
    </script>




@endpush
