@extends('layouts.app')

@section('title', 'จองห้อง')

@section('content')

    @push('style')
        <style>
            /* เซ็ตขนาดตัวอักษรในตารางเป็นเล็ก */
            #table {
                font-size: 12px;
                /* ปรับขนาดตามที่คุณต้องการ */
            }

            /* ลดขนาดตัวอักษรในส่วนของส่วนหัวของตาราง (thead) */
            #table thead th {
                font-size: 12px;
                /* ปรับขนาดตามที่คุณต้องการ */
            }

            /* ลดขนาดตัวอักษรในส่วนของข้อมูลในตาราง (tbody) */
            #table tbody td {
                font-size: 12px;
                /* ปรับขนาดตามที่คุณต้องการ */
            }
        </style>
    @endpush


    <div class="content-header">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-9">

                    <h1 class="m-0">
                        <a href="javascript:void(0);" onclick="goBack();" class="btn bg-gradient-primary">
                            <i class="fa-solid fa fa-reply"></i> กลับ
                        </a>

                        ห้อง <font class="text-danger">{{ $rooms->room_address }} ( {{ $rooms->address }} )</font>

                    </h1>


                </div><!-- /.col -->


            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">



            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">ตารางข้อมูลการจอง
                                @if ($mBookings > 0)

                                @else
                                @if ($totalValidBookings < 3)
                                    <button type="button" class="btn btn-success" id="Create">
                                        <i class="fa fa-plus"></i> เพิ่มข้อมูล
                                    </button>
                                {{-- @elseif ($totalValidBookings < 3 && $cancelledBookings < 3)
                                    <button type="button" class="btn btn-success" id="Create">
                                        <i class="fa fa-plus"></i> เพิ่มข้อมูล
                                    </button> --}}
                                @endif
                                @endif


                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table id="table" class="table table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th style="width: 3%">#</th>
                                        <th style="width: 13%">ลูกค้า</th>
                                        <th>เบอร์โทร<sup>ลูกค้า</sup></th>
                                        <th>สายงาน</th>
                                        <th>ทีม</th>
                                        <th>เบอร์โทร<sup>สายงาน</sup></th>
                                        <th>สถานะ</th>
                                        <th>วันที่<sup>เสนอราคา</sup></th>
                                        <th>วันที่<sup>จอง</sup></th>
                                        <th>วันที่<sup>ทำสัญญา</sup></th>
                                        <th>วันที่<sup>โอน</sup></th>
                                        <th>เงินจอง</th>
                                        <th>SLA</th>
                                        <th style="width: 8%">หมายเหตุ</th>
                                        <th style="width: 13%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($bookings->isEmpty())
                                        <tr>
                                            <td colspan="15" class="text-center text-danger">ไม่พบข้อมูลการจอง</td>
                                        </tr>
                                    @endif

                                    @foreach ($bookings as $booking)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>คุณ {{ $booking->customer_fname }} {{ $booking->customer_sname }}</td>
                                            <td>{{ $booking->customer_tel }}</td>
                                            <td>{{ $booking->workfield }}</td>
                                            <td>{{ $booking->team }}</td>
                                            <td>{{ $booking->team_tel }}</td>
                                            <td>
                                                @if ($booking->status == 'จองแล้ว')
                                                    <font class="text-success"> {{ $booking->status }} </font>
                                                @elseif ($booking->status == 'ทำสัญญา')
                                                    <font class="text-primary"> {{ $booking->status }} </font>
                                                @elseif ($booking->status == 'โอน')
                                                    <font class="text-success"> {{ $booking->status }} </font>
                                                @elseif ($booking->status == 'ยกเลิก')
                                                    <font class="text-danger"> {{ $booking->status }} </font>
                                                @else
                                                    <font class="text-info">{{ $booking->status }}</font>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->bid_date)
                                                    {{ date('d/m/Y', strtotime($booking->bid_date)) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->booking_date)
                                                    {{ date('d/m/Y', strtotime($booking->booking_date)) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->booking_contract)
                                                    {{ date('d/m/Y', strtotime($booking->booking_contract)) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($booking->mortgage_date)
                                                    {{ date('d/m/Y', strtotime($booking->mortgage_date)) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($booking->deposit) }}.- </td>
                                            <td class="text-danger">{{ $booking->daysOverdue }} วัน</td>
                                            <td>{{ $booking->remark }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn bg-gradient-info edit-item"
                                                        data-id="{{ $booking->id }}">แก้ไข</button>
                                                    <button class="btn bg-gradient-danger data-cancel"
                                                        data-id="{{ $booking->id }}"> ยกเลิก</button>
                                                    @if ($isRole->role_type == 'SuperAdmin')
                                                        <button class="btn bg-gradient-dark delete-item"
                                                            data-id="{{ $booking->id }}"> ลบ</button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-create">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header card-outline card-lightblue">
                            <h5 class="modal-title">เพิ่มข้อมูล</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="createForm" name="createForm" class="form-horizontal">

                            @csrf

                            <div class="modal-body">

                                <div class="box-body">

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">สถานะ</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="status_id" name="status_id">

                                                @foreach ($sutatusBid as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                            <span class="text-danger mt-1"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">*ชื่อ</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="customer_fname" type="text" value=""
                                                autocomplete="off">
                                            <span class="text-danger mt-1 fname_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">*นามสกุล</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="customer_sname" type="text" value=""
                                                autocomplete="off">
                                            <span class="text-danger mt-1 sname_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*เบอร์โทรศัพท์ลูกค้า</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="customer_tel" type="text" value=""
                                                autocomplete="off" maxlength="10">
                                            <span class="text-danger mt-1 ctel_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*สายงาน</label>
                                        <div class="col-sm-6">
                                            <select name="workfield" class="form-control">
                                                <option value="Sale-In">Sale-In</option>
                                                <option value="Outsource">Outsource</option>
                                                <option value="Walk-In">Walk-In</option>
                                            </select>
                                            <span class="text-danger mt-1"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">*Team</label>
                                        <div class="col-sm-6">
                                            <select name="agent" class="form-control">
                                                <option value="">เลือก</option>
                                                @foreach ($teams as $team)
                                                    <option value="{{ $team->team_name }}">{{ $team->team_name }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            {{-- <input class="form-control" name="agent" type="text" value=""
                                                autocomplete="off"> --}}
                                            <span class="text-danger mt-1 team_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*เบอร์โทรศัพท์สายงาน</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="agent_phone" type="text" value=""
                                                maxlength="10" autocomplete="off">
                                            <span class="text-danger mt-1 agent_phone_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่เสนอราคา</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="bid_date" type="text"
                                                value="" autocomplete="off">
                                            <span class="text-danger mt-1 bid_date_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่จอง</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="booking_date" type="text"
                                                value="" autocomplete="off">
                                            <span class="text-danger mt-1 booking_date_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่ทำสัญญา</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="booking_contract" type="text"
                                                value="" autocomplete="off">
                                            <span class="text-danger mt-1 contract_date_err"></span>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่โอน</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="mortgage_date" id="mortgage_date"  type="text" value=""  autocomplete="off">
                                            <span class="text-danger mt-1 mortgage_date_err"></span>
                                        </div>
                                    </div> --}}
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">เงินจอง</label>
                                        <div class="col-sm-6">
                                            <input class="form-control deposit" name="deposit" id="deposit"
                                                type="number" value="">
                                            <span class="text-danger mt-1 deposit_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">หมายเหตุ</label>
                                        <div class="col-sm-6">

                                            <textarea class="form-control" rows="3" name="remark" id="remark"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>
                                <button type="button" class="btn btn-success" id="savedata" value="create"><i
                                        class="fa fa-save"></i> บันทึก</button>
                            </div>

                            <input type="hidden" class="form-control" id="user_id" name="user_id" placeholder=""
                                autocomplete="off" value="{{ $dataLoginUser['user_id'] }}">

                            <input type="hidden" class="form-control" id="roomId" name="roomId" placeholder=""
                                autocomplete="off" value="{{ $rooms->id }}">
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header card-outline card-lightblue">
                            <h5 class="modal-title">แก้ไขข้อมูล</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editForm" name="editForm" class="form-horizontal">

                            @csrf
                            <div class="modal-body">

                                <div class="box-body">

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">สถานะ</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="status_id_edit" name="status_id_edit">
                                                @foreach ($status as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                            <span class="text-danger mt-1"></span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">*ชื่อ</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="customer_fname" id="customer_fname"
                                                type="text" value="" autocomplete="off">
                                            <span class="text-danger mt-1 fname_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*นามสกุล</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="customer_sname" id="customer_sname"
                                                type="text" value="" autocomplete="off">
                                            <span class="text-danger mt-1 sname_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*เบอร์โทรศัพท์ลูกค้า</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="customer_tel" id="customer_tel"
                                                type="text" value="" autocomplete="off" maxlength="10">
                                            <span class="text-danger mt-1 ctel_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*สายงาน</label>
                                        <div class="col-sm-6">
                                            <select name="workfield" id="workfield" class="form-control">
                                                <option value="Sale-In">Sale-In</option>
                                                <option value="Outsource">Outsource</option>
                                                <option value="Walk-In">Walk-In</option>
                                            </select>
                                            <span class="text-danger mt-1"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">*Team</label>
                                        <div class="col-sm-6">
                                            {{-- <input class="form-control" name="agent" id="agent" type="text"
                                                value=""> --}}
                                            <select class="form-control" id="agent" name="agent">
                                                @foreach ($teams as $team)
                                                    <option value="{{ $team->team_name }}">{{ $team->team_name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            <span class="text-danger mt-1 team_err"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">*เบอร์โทรศัพท์สายงาน</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" name="agent_phone" id="agent_phone"
                                                type="text" value="" maxlength="10">
                                            <span class="text-danger mt-1 agent_phone_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่เสนอราคา</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="bid_date_edit"
                                                id="bid_date_edit" type="text" value="">
                                            <span class="text-danger mt-1 bid_date_edit_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่จอง</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="booking_date_edit"
                                                id="booking_date_edit" type="text" value="">
                                            <span class="text-danger mt-1 booking_date_edit_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่ทำสัญญา</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="booking_contract_edit"
                                                id="booking_contract_edit" type="text" value="">
                                            <span class="text-danger mt-1 contract_date_edit_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันที่โอน</label>
                                        <div class="col-sm-6">
                                            <input class="form-control datepicker" name="mortgage_date_edit"
                                                id="mortgage_date_edit" type="text" value="">
                                            <span class="text-danger mt-1 mortgage_date_edit_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">เงินจอง</label>
                                        <div class="col-sm-6">
                                            <input class="form-control deposit" name="deposit" id="deposit"
                                                type="number" value="">
                                            <span class="text-danger mt-1 deposit_err"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">หมายเหตุ</label>
                                        <div class="col-sm-6">

                                            <textarea class="form-control" rows="3" name="remark_edit" id="remark_edit"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>
                                <button type="button" class="btn btn-success" id="updatedata" value="update"><i
                                        class="fa fa-save"></i> อัพเดท</button>
                            </div>
                            <input type="hidden" class="form-control" id="user_id" name="user_id" placeholder=""
                                autocomplete="off" value="{{ $dataLoginUser['user_id'] }}">
                            <input type="hidden" class="form-control" id="id" name="id" placeholder=""
                                autocomplete="off" value="">
                            <input type="hidden" class="form-control" id="rooms_id" name="rooms_id" placeholder=""
                                autocomplete="off" value="">
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->


        </div><!-- /.container-fluid -->
    </section>

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
            const user_id = {{ $dataLoginUser['user_id'] }};
            const rooms_id = {{ $rooms->id }};

            //ลบการจอง
            $('body').on('click', '.delete-item', function() {
                const id = $(this).data("id");
                const cancellationReason = $('#cancellationReason').val();
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
                            url: '/api/rooms/booking/destroy/' + id + '/' + user_id,
                            data: {
                                cancellationReason: cancellationReason
                            },
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

                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/rooms/booking') }}' + "/" +
                                        rooms_id;
                                }, 1500);

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

            //ยกเลิกการจอง
            $('body').on('click', '.data-cancel', function() {
                const id = $(this).data("id");

                Swal.fire({
                    title: 'คุณแน่ใจไหม?',
                    html: '<label for="cancellationReason">โปรดกรอกหมายเหตุ:</label><textarea id="cancellationReason" class="swal2-textarea" placeholder="กรอกหมายเหตุ" style="display: flex; height: auto;"></textarea><label for="cancellationReason">อีเมล์:</label><input type="email" id="mail" class="swal2-textarea" placeholder="กรอกอีเมล์" style="display: flex; height: auto;">',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const cancellationReason = $('#cancellationReason').val();
                        const mail = $('#mail').val();
                        // ทำตามที่คุณต้องการกับ cancellationReason
                        $.ajax({
                            type: "POST",
                            url: '/api/rooms/booking/cancel/' + id + '/' + user_id + '/' +
                                rooms_id,
                            data: {
                                cancellationReason: cancellationReason,
                                mail: mail
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'ยกเลิก การจองสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/rooms/booking') }}' + "/" +
                                        rooms_id;
                                }, 1500);
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


            //Booking
            $('#Create').click(function() {
                $('#savedata').val("create");
                $('#createForm').trigger("reset");
                $('#modal-create').modal('show');
            });


            //Save
            $('#savedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');

                //console.log(e);
                const _token = $("input[name='_token']").val();
                const user_id = $("#user_id").val();
                const roomId = $("#roomId").val();
                const status_id = $("#status_id").val();
                const customer_fname = $("#customer_fname").val();
                const customer_sname = $("#customer_sname").val();
                const customer_tel = $("#customer_tel").val();
                const workfield = $("#workfield").val();
                const agent = $("#agent").val();
                const booking_status = $("#booking_status").val();
                const agent_phone = $("#agent_phone").val();
                const bid_date = $("#bid_date").val();
                const booking_contract = $("#booking_contract").val();
                const mortgage_date = $("#mortgage_date").val();
                const deposit = $("#deposit").val();


                console.log("Data being sent: " + $('#createForm').serialize());

                $.ajax({

                    data: $('#createForm').serialize(),
                    url: "{{ route('room.booking.insert') }}",
                    type: "POST",
                    dataType: "json",

                    success: function(data) {
                        // console.log(data);
                        if (data.success = true) {

                            if ($.isEmptyObject(data.error)) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                });

                                $('#createForm')[0].reset();
                                $('#modal-create').modal('hide');

                                // table.draw();

                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('rooms/booking') }}' + "/" + rooms_id;
                                }, 1500);


                            } else {
                                // console.log(data.error);
                                printErrorMsg(data.error);
                                $('#savedata').html('ลองอีกครั้ง');
                                $('#createForm').trigger("reset");
                                $('.fname_err').text(data.error.customer_fname);
                                $('.sname_err').text(data.error.customer_sname);
                                $('.ctel_err').text(data.error.customer_tel);
                                $('.team_err').text(data.error.agent);
                                $('.agent_phone_err').text(data.error.agent_phone);
                                $('.bid_date_err').text(data.error.bid_date);
                                $('.contract_date_err').text(data.error.booking_contract);
                                $('.booking_date_err').text(data.error.booking_date);
                                $('.deposit_err').text(data.error.deposit);
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'error',
                                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                                    html: `เนื่องจากกรอกข้อมูลไม่ครบถ้วน`,
                                    showConfirmButton: true,
                                    timer: 1500
                                });
                            }

                        }
                    },

                    statusCode: {
                        400: function() {
                            $('#savedata').html('ลองอีกครั้ง');
                            // $('.name_err').text("ซ้ำ");
                            Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'ไม่สามารถจองได้จำนวนลูกค้าเต็ม',
                                showConfirmButton: true,
                                timer: 1500
                            });
                        }
                    }

                });
            });

            //ShowEdit
            $('body').on('click', '.edit-item', function() {

                const id = $(this).data('id');


                $('#modal-edit').modal('show');

                $.get('/api/rooms/booking/edit/' + id, function(data) {
                    //console.log(data.booking_status);
                    $('#id').val(id);
                    $('#rooms_id').val(data.rooms_id);
                    $('#customer_sname').val(data.customer_sname);
                    $('#customer_fname').val(data.customer_fname);
                    $('#customer_tel').val(data.customer_tel);

                    $('#agent_phone').val(data.team_tel);
                    $('#bid_date_edit').val(data.bid_date);
                    $('#booking_date_edit').val(data.booking_date);
                    $('#booking_contract_edit').val(data.booking_contract);
                    $('#mortgage_date_edit').val(data.mortgage_date);
                    $('#deposit').val(data.deposit);
                    $('#remark_edit').val(data.remark);
                    $('#status_id_edit option[value="' + data.booking_status + '"]').prop(
                        'selected', true);
                    updateOnOffEdit();
                    $('#workfield option[value="' + data.workfield + '"]').prop('selected', true);
                    $('#agent option[value="' + data.team + '"]').prop('selected', true);


                });
            });

            //updateData
            $('#updatedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const id = $("#id").val();

                //console.log(id);
                $.ajax({
                    data: $('#editForm').serialize(),
                    url: '/api/rooms/booking/update/' + id,
                    type: "POST",
                    dataType: 'json',

                    success: function(data) {
                        //console.log(data);
                        if (data.success = true) {

                            if ($.isEmptyObject(data.error)) {
                                Swal.fire({

                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 2500
                                });
                                $('#modal-edit').trigger("reset");
                                $('#modal-edit').modal('hide');
                                //tableUser.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('rooms/booking') }}' + "/" + rooms_id;
                                }, 1500);
                            } else {
                                printErrorMsg2(data.error);
                                $('.mortgage_date_edit_err').text(data.error
                                    .mortgage_date_edit);
                                $('.booking_date_edit_err').text(data.error.booking_date_edit);
                                $('.contract_date_edit_err').text(data.error
                                    .booking_contract_edit);
                                $('.deposit_err').text(data.error.deposit);
                                $('#modal-edit').trigger("reset");
                                $('#updatedata').html('ลองอีกครั้ง');

                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'error',
                                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                                    html: `เนื่องจากกรอกข้อมูลไม่ครบถ้วน`,
                                    timer: 2500
                                });
                            }

                        } else {
                            Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                showConfirmButton: true,
                                timer: 2500
                            });
                            $('#editForm').trigger("reset");
                        }


                    },

                });
            });


            function printErrorMsg(msg) {
                $.each(msg, function(key, value) {
                    //console.log(key);
                    $('.' + key + '_err').text(value);
                });
            }

            function printErrorMsg2(msg) {
                $.each(msg, function(key, value) {
                    //console.log(key);
                    $('.' + key + '_err2').text(value);
                });
            }


            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // รูปแบบวันที่
                autoclose: true,
                todayHighlight: true
            });

            function updateOnOff() {
                const selectedValue = $('#status_id').val();
                $('.datepicker').prop('readonly', false);

                switch (selectedValue) {
                    case '8': //ออกใบเสนอราคา
                        $('[name="booking_date"],[name="booking_contract"],[name="mortgage_date"]').prop('readonly',
                            true);
                        break;
                    case '2': //จองแล้ว
                        $('[name="bid_date"],[name="booking_contract"],[name="mortgage_date"]').prop('readonly',
                            true);
                        break;
                    case '3': //ทำสัญญาแล้ว
                        $('[name="bid_date"],[name="booking_date"],[name="mortgage_date"]').prop('readonly', true);
                        break;
                    case '4': //โอน
                        $('[name="bid_date"],[name="booking_date"],[name="booking_contract"]').prop('readonly',
                            true);
                        break;
                }
            }

            updateOnOff();
            $('#status_id').change(updateOnOff);


            function updateOnOffEdit() {
                const selectedValue2 = $('#status_id_edit').val();
                $('.datepicker').prop('readonly', false);
                $('.deposit').prop('readonly', false);
                switch (selectedValue2) {
                    case '8': //ออกใบเสนอราคา
                        $('[name="booking_date_edit"],[name="booking_contract_edit"],[name="mortgage_date_edit"],[name="deposit"]')
                            .prop('readonly', true);
                        break;
                    case '2': //จองแล้ว
                        $('[name="bid_date_edit"],[name="booking_contract_edit"],[name="mortgage_date_edit"]').prop(
                            'readonly', true);
                        break;
                    case '3': //ทำสัญญาแล้ว
                        $('[name="bid_date_edit"],[name="booking_date_edit"],[name="mortgage_date_edit"],[name="deposit"]')
                            .prop('readonly', true);
                        break;
                    case '4': //โอน
                        $('[name="bid_date_edit"],[name="booking_date_edit"],[name="booking_contract_edit"],[name="deposit"]')
                            .prop('readonly', true);
                        break;
                }
            }

            updateOnOffEdit();
            $('#status_id_edit').change(updateOnOffEdit);

            function updateOnOffAdd() {
                const selectedValue2 = $('#status_id').val();
                $('.datepicker').prop('readonly', false);
                $('.deposit').prop('readonly', false);
                switch (selectedValue2) {
                    case '8': //ออกใบเสนอราคา
                        $('[name="booking_date"],[name="booking_contract"],[name="mortgage_date"],[name="deposit"]')
                            .prop('readonly', true);
                        break;
                    case '2': //จองแล้ว
                        $('[name="bid_date"],[name="booking_contract"],[name="mortgage_date"]').prop(
                            'readonly', true);
                        break;
                    case '3': //ทำสัญญาแล้ว
                        $('[name="bid_date"],[name="booking_date"],[name="mortgage_date"],[name="deposit"]')
                            .prop('readonly', true);
                        break;
                }
            }
            updateOnOffAdd();
            $('#status_id').change(updateOnOffAdd);

        });
    </script>
@endpush
