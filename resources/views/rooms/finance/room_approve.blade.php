@extends('layouts.app')

@section('title', 'ห้องรอการอนุมัติ')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">

                    {{-- <a type="button" class="btn bg-gradient-primary" href="{{ route('room') }}">
                            <i class="fa fa-reply"></i> กลับ
                        </a> --}}
                    <h1 class="m-0">ห้องที่รอการอนุมัติ ทั้งหมด</h1>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid ">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">ค้นหา ห้องรอการอนุมัติ</h3>

                        </div>
                        <form action="{{ route('room.approve.search') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row justify-content-center"> {{-- justify-content-center --}}
                                    <label for="project_id" class="col-sm-1 col-form-label text-right">โครงการ </label>
                                    <select name="project_id" id="project_id" class="col-sm-2 form-control">
                                        <option value="">เลือก</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="box-footer text-center">
                                    <button type="submit" class="btn btn-success">ค้นหา</button>
                                    <a href="{{ route('room.approve') }}" type="button" class="btn btn-danger">เคลียร์</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            {{--  room.approve.search  --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">จำนวน <font class="text-danger"> {{ $roomsCount }} </font>
                                ห้อง
                                @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->dept == 'Finance')
                                    <button class="btn bg-gradient-success"
                                        id="cancle-rooms-selected">(ยืนยันอนุมัติ)</button>
                                @endif

                            </h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="table" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr class="text-center">
                                        <th><input type="checkbox" id="select-all-btn"> CheckAll</th>
                                        {{-- <th>No</th> --}}
                                        {{-- <th>ID<sup>Room</sup></th> --}}
                                        <th>โครงการ</th>
                                        <th>ห้องเลขที่</th>
                                        <th>บ้านเลขที่</th>
                                        <th>รายละเอียดอาคาร</th>
                                        <th>ขนาด<sup>(ตรม.)</sup></th>
                                        <th>ราคา</th>

                                        <th>ราคา<sup>(1)</sup></th>
                                        <th>ราคา<sup>(2)</sup></th>
                                        <th>ราคา<sup>(3)</sup></th>
                                        <th>ราคา<sup>(4)</sup></th>
                                        <th>ราคา<sup>(5)</sup></th>
                                        <th>ราคา<sup>(ทีมตัดทุน)</sup></th>
                                        @if ($isRole->role_type == 'SuperAdmin')
                                            <th>Action</th>
                                        @endif
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($rooms as $room)
                                        <tr class="text-center">
                                            <td>
                                                <input type="checkbox" class="room-checkbox" data-id="{{ $room->id }}">
                                            </td>
                                            {{-- <td>{{ $room->id }}</td> --}}
                                            <td>{{ optional($room->project)->name }}</td>
                                            <td>{{ $room->room_address }}</td>
                                            <td>{{ $room->address }}</td>
                                            <td>{{ 'อาคาร ' . $room->plan->name . ', ตึก ' . $room->building . ', ชั้น ' . $room->floor }}
                                            </td>
                                            <td>{{ number_format($room->area, 2) }}</td>
                                            <td>{{ number_format($room->price) }}</td>
                                            <td>{{ number_format($room->special_price1) }}</td>
                                            <td>{{ number_format($room->special_price2) }}</td>
                                            <td>{{ number_format($room->special_price3) }}</td>
                                            <td>{{ number_format($room->special_price4) }}</td>
                                            <td>{{ number_format($room->special_price5) }}</td>
                                            <td>{{ number_format($room->special_price_team) }}</td>
                                            @if ($isRole->role_type == 'SuperAdmin')
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn bg-gradient-danger delete-item"
                                                            data-id="{{ $room->id }}"> ลบ</button>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
        $(document).ready(function() {
            const user_id = {{ $dataLoginUser['user_id'] }};
            //ลบการจอง
            $('body').on('click', '.delete-item', function() {
                const id = $(this).data("id");
                console.log(id);
                Swal.fire({
                    title: 'คุณแน่ใจไหม? ',
                    text: "หากต้องการลบข้อมูลนี้ โปรดยืนยัน การลบข้อมูล",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {

                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: '/api/rooms/delete/' + id + '/' + user_id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบข้อมูลสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout(
                                    "location.href = '{{ route('room.approve') }}';",
                                    1500);

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
            // เมื่อคลิกที่ปุ่ม "เลือกทั้งหมด"
            $('#select-all-btn').click(function() {
                // ตรวจสอบสถานะของปุ่ม "เลือกทั้งหมด"
                if ($(this).hasClass('selected')) {
                    // ถ้าปุ่มมีคลาส 'selected' ให้ยกเลิกการเลือกทั้งหมด
                    $('.room-checkbox').prop('checked', false);
                } else {
                    // ถ้าปุ่มไม่มีคลาส 'selected' ให้เลือกทั้งหมด
                    $('.room-checkbox').prop('checked', true);
                }
                // สลับสถานะของปุ่ม "เลือกทั้งหมด"
                $(this).toggleClass('selected');
            });

            // เมื่อคลิกที่ checkbox ของแต่ละรายการ
            $('.room-checkbox').click(function() {
                // ตรวจสอบว่า checkbox ทั้งหมดถูกเลือกหรือไม่
                if ($('.room-checkbox:checked').length === $('.room-checkbox').length) {
                    // ถ้า checkbox ทั้งหมดถูกเลือกให้เพิ่มคลาส 'selected' ให้กับปุ่ม "เลือกทั้งหมด"
                    $('#select-all-btn').addClass('selected');
                } else {
                    // ถ้าไม่ใช่ checkbox ทั้งหมดถูกเลือกให้ลบคลาส 'selected' ของปุ่ม "เลือกทั้งหมด"
                    $('#select-all-btn').removeClass('selected');
                }
            });
            $('#delete-selected').click(function() {
                var selectedApprove = [];

                // หา checkbox ที่ถูกเลือกและเก็บค่า data-id ลงใน selectedRooms
                $('.room-checkbox:checked').each(function() {
                    selectedApprove.push($(this).data('id'));
                });

                // ตรวจสอบว่ามีรายการถูกเลือกหรือไม่
                if (selectedApprove.length === 0) {
                    // ถ้าไม่มีรายการถูกเลือก ให้แสดง SweetAlert แจ้งเตือน
                    Swal.fire({
                        icon: 'info',
                        title: 'ไม่มีรายการที่เลือก',
                        text: 'กรุณาเลือกรายการที่ต้องการยืนยันอนุมัติ',
                    });
                } else {
                    // ถ้ามีรายการถูกเลือก ให้แสดง SweetAlert เพื่อยืนยันการลบ
                    Swal.fire({
                        title: 'คุณแน่ใจไหม?',
                        text: 'หากต้องการ อนุมัติ รายการที่เลือก โปรดยืนยันการอนุมัติ',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonText: 'ยืนยัน'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ทำ AJAX request เพื่อลบรายการที่เลือก
                            $.ajax({
                                type: 'POST', // หรือ 'DELETE' ตามที่คุณใช้
                                //route('room.update.status_id')
                                url: '{{ route('room.approve.update') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    selected_rooms: selectedApprove
                                },
                                success: function(data) {
                                    // ตรวจสอบคำตอบจากเซิร์ฟเวอร์ และแสดง SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ยืนยันการอนุมัติห้องสำเร็จ!',
                                        showConfirmButton: true,
                                        timer: 2500
                                    });
                                    setTimeout(
                                        "location.href = '{{ route('room.approve') }}';",
                                        1500);
                                    // location.reload();

                                },
                                error: function(xhr, status, error) {
                                    // แสดงข้อผิดพลาดหรือดำเนินการตามที่คุณต้องการในกรณีที่เกิดข้อผิดพลาด
                                }
                            });
                        }
                    });
                }
            });

            $('#cancle-rooms-selected').click(function() {
                var selectedApprove = [];

                // หา checkbox ที่ถูกเลือกและเก็บค่า data-id ลงใน selectedApprove
                $('.room-checkbox:checked').each(function() {
                    selectedApprove.push($(this).data('id'));
                });

                // ตรวจสอบว่ามีรายการถูกเลือกหรือไม่
                if (selectedApprove.length === 0) {
                    // ถ้าไม่มีรายการถูกเลือก ให้แสดง SweetAlert แจ้งเตือน
                    Swal.fire({
                        icon: 'info',
                        title: 'ไม่มีรายการที่เลือก',
                        text: 'กรุณาเลือกรายการที่ต้องการยืนยันอนุมัติ',
                    });
                } else {
                    // ถ้ามีรายการถูกเลือก ให้แสดง SweetAlert เพื่อยืนยันการลบ
                    Swal.fire({
                        title: 'คุณแน่ใจไหม?',
                        text: 'หากต้องการ อนุมัติ โปรดยืนยัน',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonText: 'ยืนยัน'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // ทำ AJAX request เพื่อลบรายการที่เลือก
                            $.ajax({
                                type: 'POST', // หรือ 'DELETE' ตามที่คุณใช้
                                url: '{{ route('room.approve.update') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    selected_rooms: selectedApprove
                                },
                                success: function(data) {
                                    // ตรวจสอบคำตอบจากเซิร์ฟเวอร์ และแสดง SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ยืนยันการอนุมัติห้องสำเร็จ!',
                                        showConfirmButton: true,
                                        timer: 2500
                                    });
                                    setTimeout(
                                        "location.href = '{{ route('room.approve') }}';",
                                        1500);
                                    // location.reload();

                                },
                                error: function(xhr, status, error) {
                                    // แสดงข้อผิดพลาดหรือดำเนินการตามที่คุณต้องการในกรณีที่เกิดข้อผิดพลาด
                                }
                            });
                        }
                    });
                }
            });

            function printErrorMsg(msg) {
                $.each(msg, function(key, value) {
                    //console.log(key);
                    $('.' + key + '_err').text(value);
                });
            }

        });
    </script>
@endpush
