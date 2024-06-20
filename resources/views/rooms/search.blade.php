@extends('layouts.app')

@section('title', 'ห้อง')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}
    @push('style')
        <style>
            .phone-icon {
                position: relative;
                display: inline-block;
            }

            .phone-number {
                position: absolute;
                top: 100%;
                left: 50%;
                transform: translateX(-50%);
                background-color: #fff;
                border: 1px solid #ccc;
                padding: 5px;
                border-radius: 5px;
                display: none;
                /* เริ่มต้นให้ข้อมูลเบอร์โทรซ่อนไว้ */
            }

            .phone-icon:hover .phone-number {
                display: block;
                /* เมื่อโฮเวอร์ไปที่ไอคอนโทรศัพท์จะแสดงข้อมูลเบอร์โทร */
            }

            .swal-wide {
                width: 60% !important;
            }
        </style>
        <style>
            #table thead th {
                font-size: 13px;
            }

            #table tbody td {
                font-size: 13px;
            }

            #badge {
                font-size: 12px;
                /* color: #000 !important; */
            }
        </style>
    @endpush
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">

                    {{-- <a type="button" class="btn bg-gradient-primary" href="{{ route('room') }}">
                            <i class="fa fa-reply"></i> กลับ
                        </a> --}}
                    <h1 class="m-0">ห้อง ทั้งหมด
                        @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin')
                            <button type="button" class="btn bg-gradient-primary" id="Create">
                                <i class="fa fa-plus"></i> เพิ่มห้อง <sup>Manual</sup>
                            </button>
                            <button type="button" class="btn bg-gradient-primary" id="ImportExcel">
                                <i class="fa fa-file-excel"></i> เพิ่มห้อง <sup>ImportExcel</sup>
                            </button>
                            <button type="button" class="btn bg-gradient-primary" data-toggle="modal"
                                data-target="#modal-update-excel">
                                <i class="fa fa-file-excel"></i> อัพเดทราคาห้อง <sup>ImportExcel</sup>
                            </button>
                        @endif
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">ค้นหา ห้อง</h3>

                        </div>
                        <form action="{{ route('room.search') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col sm-4">
                                        <label for="" class="col-md-12 col-form-label ">โครงการ</label>
                                        <div class="col-md-12 my-auto">
                                            <select name="project_id" id="project_id" class="form-control">
                                                <option value="ทั้งหมด">ทั้งหมด</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">ชื่อลูกค้า</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="user_name" type="text" value=""
                                                placeholder="ชื่อลูกค้า">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">ช่องทาง</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="fixseller" type="text" value=""
                                                placeholder="ช่องทาง">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">วันที่</label>
                                        <div class="col-md-12 my-auto">
                                            <select name="dateselect" id="dateselect" class="form-control">
                                                <option value="bid_date">วันที่เสนอราคา</option>
                                                <option value="mortgaged_date">วันที่โอน</option>
                                                <option value="booking_date">วันที่จอง</option>
                                                <option value="contract_date">วันที่ทำสัญญา</option>
                                                <option value="" selected>ทั้งหมด</option>

                                            </select>

                                        </div>
                                    </div>
                                    <div class="col sm-4">
                                        <label for="" class="col-md-12 col-form-label ">ห้องเลขที่</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="room_address" type="text" value=""
                                                placeholder="ห้องเลขที่">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">เบอร์โทรศัพท์</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="phone" type="text" value=""
                                                placeholder="เบอร์โทรศัพท์">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">ราคาเริ่มต้น</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="startprice" type="text" value=""
                                                placeholder="ราคาเริ่มต้น">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">วันที่เริ่มต้น</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control datepicker" name="startdate" id="startdate"
                                                type="text" value="" placeholder="วันที่เริ่มต้น">
                                        </div>

                                    </div>
                                    <div class="col sm-4">
                                        <label for="" class="col-md-12 col-form-label ">บ้านเลขที่</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="address" type="text" value=""
                                                placeholder="บ้านเลขที่">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">MNG</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="agent" type="text" value=""
                                                placeholder="MNG">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">ถึงช่วงราคา</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="endprice" type="text" value=""
                                                placeholder="ถึงช่วงราคา">
                                        </div>
                                        <br>
                                        <label for="" class="col-md-12 col-form-label ">ถึงวันที่</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control datepicker" name="enddate" id="enddate"
                                                type="text" value="" placeholder="ถึงวันที่">
                                        </div>
                                    </div>
                                </div>

                                <div class="row search-form form-group">
                                    <div class="col-lg-12">
                                        <label for="" class="col-md-12 col-form-label ">สถานะ</label>
                                        <div class="col-md-12 my-auto">

                                            @foreach ($status as $item)
                                                <input type="checkbox" name="status[]" class="status"
                                                    id="{{ $item->id }}" value="{{ $item->id }}"
                                                    @isset($_GET['status'])
                                                    {{ in_array($item->id, $_GET['status']) ? 'checked' : '' }}
                                                    @else
                                                    {{ 'checked' }}
                                                    @endisset>
                                                <label for="{{ $item->id }}">{{ $item->name }}</label>
                                            @endforeach

                                            <a href="javascript:;" id="toggle_check" style=""
                                                class="btn btn-light btn-xs">Uncheck all</a>
                                        </div>

                                        <div class="box-footer text-center">
                                            <button type="submit" class="btn bg-gradient-success"><i
                                                    class="fa fa-search"></i>
                                                ค้นหา</button>
                                            <a href="{{ route('room') }}" type="button"
                                                class="btn bg-gradient-danger"><i class="fa fa-refresh"></i> เคลียร์</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>

                    <!--Table-->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-outline card-lightblue">
                                    <h3 class="card-title">จำนวน <font class="text-danger"> {{ $roomsCount }} </font>
                                        ห้อง
                                        @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                            <button class="btn bg-gradient-danger" id="cancle-rooms-selected">(ลบ/คืนห้อง)
                                                รายการที่เลือก</button>
                                        @endif
                                        @if ($isRole->role_type == 'SuperAdmin')
                                            <a type="button" id="export2" class="btn btn-sm bg-gradient-primary"><i
                                                    class="fa fa-file-excel"></i>
                                                ExportExcel </a>
                                        @endif
                                    </h3>

                                </div>

                                <div class="card-body table-responsive">


                                    <table id="table" class="table table-hover table-striped text-nowrap">
                                        <thead>
                                            <tr class="text-center">
                                                @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                    <th><input type="checkbox" id="select-all-btn"> Check</th>
                                                @endif
                                                {{-- <th>No</th> --}}
                                                <th>ID<sup>Room</sup></th>
                                                <th>โครงการ</th>
                                                <th>ห้องเลขที่</th>
                                                <th>บ้านเลขที่</th>
                                                <th>รายละเอียดอาคาร</th>
                                                <th>ขนาด<sup>(ตรม.)</sup></th>
                                                <th>ราคา</th>
                                                @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->dept == 'Finance')
                                                    <th>ราคา<sup>(1)</sup></th>
                                                    <th>ราคา<sup>(2)</sup></th>
                                                    <th>ราคา<sup>(3)</sup></th>
                                                    <th>ราคา<sup>(4)</sup></th>
                                                    <th>ราคา<sup>(5)</sup></th>
                                                    <th>ราคา<sup>(ทีมตัดทุน)</sup></th>
                                                @endif
                                                <th>สถานะห้อง</th>
                                                <th>วันโอน</th>
                                                @if ($isRole->role_type != 'Sale' && $isRole->role_type != 'User')
                                                    <th>หมายเหตุ</th>
                                                @endif
                                                @if ($isRole->role_type == 'Sale')
                                                    <th>สถานะลูกค้า</th>
                                                @endif
                                                @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                    <th>ช่องทางการขาย</th>
                                                    <th>ข้อมูลลูกค้า</th>
                                                @endif
                                                @if (
                                                    $isRole->role_type == 'SuperAdmin' ||
                                                        $isRole->role_type == 'Admin' ||
                                                        $isRole->role_type == 'Staff' ||
                                                        $isRole->dept == 'Finance')
                                                    <th class="text-center">Action</th>
                                                @else
                                                    <th class="text-center">Detail</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rooms as $room)
                                                <tr class="text-center">

                                                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                        <td>
                                                            <input type="checkbox" class="room-checkbox"
                                                                data-id="{{ $room->id }}">
                                                        </td>
                                                    @endif

                                                    <td>{{ $room->id }}</td>
                                                    <td>{{ $room->project->name ?? '' }}</td>
                                                    <td>{{ $room->room_address }}</td>
                                                    <td>{{ $room->address }} </td>
                                                    <td>{{ 'อาคาร ' . optional($room->plan)->name ?? '' . ', ตึก ' . $room->building . ', ชั้น ' . $room->floor }}
                                                    </td>
                                                    <td>{{ number_format($room->area, 2) }}</td>
                                                    <td>{{ number_format($room->price) }}</td>

                                                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->dept == 'Finance')
                                                        <td>{{ number_format($room->special_price1) }}</td>
                                                        <td>{{ number_format($room->special_price2) }}</td>
                                                        <td>{{ number_format($room->special_price3) }}</td>
                                                        <td>{{ number_format($room->special_price4) }}</td>
                                                        <td>{{ number_format($room->special_price5) }}</td>
                                                        <td>{{ number_format($room->special_price_team) }}</td>
                                                    @endif
                                                    <td>
                                                        @if (optional($room->status)->name == 'จองแล้ว')
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-success">{{ optional($room->status)->name }}</span>
                                                        @elseif (optional($room->status)->name == 'ทำสัญญา')
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-primary">{{ optional($room->status)->name }}</span>
                                                        @elseif(optional($room->status)->name == 'โอน')
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-info">{{ optional($room->status)->name }}</span>
                                                        @elseif(optional($room->status)->name == 'ออกใบเสนอราคา')
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-warning">{{ optional($room->status)->name }}</span>
                                                        @elseif(optional($room->status)->name == 'คืนห้อง')
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-danger">{{ optional($room->status)->name }}</span>
                                                        @elseif(optional($room->status)->name == 'ไม่พร้อมขาย')
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-dark">{{ optional($room->status)->name }}</span>
                                                        @else
                                                            <span id="badge"
                                                                class="badge rounded-pill bg-secondary">{{ optional($room->status)->name }}</span>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if (!empty($room->booking) && optional($room->booking)->mortgage_date != '')
                                                            {{ date('d/m/Y', strtotime(optional($room->booking)->mortgage_date)) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    @if ($isRole->role_type != 'Sale' && $isRole->role_type != 'User')
                                                        <td>
                                                            @if ($room->remarkshow)
                                                                <font class="text-danger">{{ $room->remarkshow }}</font>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if ($isRole->role_type == 'Sale')
                                                        <td>
                                                            @if (!empty($room->booking))
                                                                <button onclick="showBookingInfoSale({{ $room->id }})"
                                                                    class="btn bg-gradient-primary btn-xs"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="ชื่อลูกค้า">
                                                                    <i class="fa fa-address-book"></i> ข้อมูลการจอง
                                                                </button>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @endif
                                                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                        <td>

                                                            @if ($room->fixseller)
                                                                {{ $room->fixseller }}
                                                            @else
                                                                -
                                                            @endif


                                                        </td>
                                                        <td>

                                                            @if (!empty($room->booking))
                                                            @if (optional($room->status)->name != 'ห้องว่าง')
                                                                <button onclick="showBookingInfo({{ $room->id }})"
                                                                    class="btn bg-gradient-primary btn-xs"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="ชื่อลูกค้า">
                                                                    <i class="fa fa-address-book"></i> ข้อมูลลูกค้า
                                                                </button>
                                                            @else
                                                                -
                                                            @endif
                                                            @endif
                                                            @if ($isRole->role_type == 'Sale')
                                                            @endif


                                                        </td>
                                                    @endif




                                                    @if (
                                                        $isRole->role_type == 'SuperAdmin' ||
                                                            $isRole->role_type == 'Staff' ||
                                                            $isRole->dept == 'Finance' ||
                                                            $isRole->role_type == 'Admin')

                                                        <td>
                                                            @if (optional($room->status)->name != 'คืนห้อง')
                                                                @if ($isRole->dept != 'Finance')
                                                                    @if (optional($room->status)->name == 'โอน')
                                                                        <button
                                                                            class="btn bg-gradient-secondary btn-xs edit-item"
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            title="จอง" disabled>
                                                                            <i class="fa fa-calendar-plus">
                                                                            </i>
                                                                            จองห้อง
                                                                        </button>
                                                                    @else
                                                                        <a href="{{ url('/rooms/booking/' . $room->id) }}"
                                                                            class="btn bg-gradient-warning btn-xs edit-item"
                                                                            data-toggle="tooltip" data-placement="top"
                                                                            title="จอง">
                                                                            <i class="fa fa-calendar-plus">
                                                                            </i>
                                                                            จองห้อง
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            <a href="{{ url('/rooms/edit/' . $room->id) }}"
                                                                class="btn bg-gradient-info btn-xs edit-item"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="แก้ไข">
                                                                <i class="fa fa-pencil-square">
                                                                </i>
                                                                แก้ไขห้อง
                                                            </a>


                                                        </td>
                                                    @else
                                                        <td>
                                                            <a href="{{ url('/rooms/detail/' . $room->id) }}"
                                                                class="btn bg-gradient-info btn-xs edit-item"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="ดูรายละเอียด">
                                                                <i class="fa fa-th-list">
                                                                </i>
                                                                ดูรายละเอียดห้อง
                                                            </a>

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
                    <br>

                    <div class="modal fade" id="modal-create">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">เพิ่มข้อมูล ห้อง</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="createForm" name="createForm" class="form-horizontal">
                                    {{-- <form id="createForm" name="createForm"  method="post" action="{{route('project.insert')}}" class="form-horizontal"> --}}
                                    @csrf

                                    <input type="hidden" class="form-control" id="user_id" name="user_id"
                                        value="{{ $dataLoginUser['user_id'] }}">
                                    <div class="modal-body">

                                        <div class="box-body">
                                            <h5 class="modal-title text-info">ข้อมูลห้อง</h5>

                                            <div class="row">
                                                <div class="col-sm-4">

                                                    <div class="form-group">
                                                        <label>โครงการ</label>
                                                        <select class="form-control" id="projectSelect"
                                                            name="project_id">
                                                            <option value="">เลือก</option>
                                                            @foreach ($projects as $project)
                                                                <option value="{{ $project->id }}">{{ $project->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-danger mt-1 project_id_err"></small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Plan</label>
                                                        <select class="form-control" id="planSelect" name="plan_id">
                                                            <option value="">เลือก</option>
                                                        </select>
                                                        <small class="text-danger mt-1 plan_id_err"></small>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Type</label>
                                                        <select class="form-control" id="room_type" name="room_type">
                                                            <option value="">เลือก</option>
                                                            <option value="1">Condo</option>
                                                            <option value="2">บ้านเดี่ยว</option>
                                                            <option value="3">ทาวน์โฮม</option>
                                                            <option value="4">Duplex</option>
                                                            <option value="5">Shop House</option>
                                                            <option value="6">Pent House</option>
                                                        </select>
                                                        <small class="text-danger mt-1 room_type_err"></small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">

                                                    <div class="form-group">
                                                        <label>ชั้น</label>
                                                        <input type="text" class="form-control" id="floor"
                                                            name="floor" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 floor_err"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>อาคาร</label>
                                                        <input type="text" class="form-control" id="building"
                                                            name="building" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 building_err"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>ห้องเลขที่</label>
                                                        <input type="text" class="form-control" id="room_address"
                                                            name="room_address" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 room_address_err"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">

                                                    <div class="form-group">
                                                        <label>บ้านเลขที่</label>
                                                        <input type="text" class="form-control" id="address"
                                                            name="address" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 address_err"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>ทิศ/ฝั่ง</label>
                                                        <input type="text" class="form-control" id="direction"
                                                            name="direction" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 direction_err"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>กุญแจ หน้า/นอน/บลอค <sup>(จำนวน)</sup></label>
                                                        <input type="number" class="form-control" id="key"
                                                            name="key" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 key_err"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">

                                                    <div class="form-group">
                                                        <label>คีย์การ์ด P/B <sup>(จำนวน)</sup></label>
                                                        <input type="number" class="form-control" id="keycard"
                                                            name="keycard" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 keycard_err"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>ราคาห้อง <sup>(บาท)</sup></label>
                                                        <input type="number" class="form-control" id="price"
                                                            name="price" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 price_err"></p>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>ขนาด <sup>(ตร.ม.)</sup></label>
                                                        <input type="text" class="form-control" id="area"
                                                            name="area" placeholder="" autocomplete="off">
                                                        <p class="text-danger mt-1 area_err"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="modal-title text-info">เฟอร์นิเจอร์</h5>

                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="form-check">
                                                        <div class="row">
                                                            @foreach ($furniture as $item)
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="furniture[]"
                                                                        id="furniture_{{ $item->id }}"
                                                                        value="{{ $item->id }}">
                                                                    <label
                                                                        for="{{ $item->id }}">{{ $item->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <h5 class="modal-title text-info">เครื่องใช้ไฟฟ้า</h5>

                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="form-check">
                                                        <div class="row">
                                                            @foreach ($facility as $item)
                                                                <div class="col-md-3">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="facility[]"
                                                                        id="facility{{ $item->id }}"
                                                                        value="{{ $item->id }}">
                                                                    <label
                                                                        for="{{ $item->id }}">{{ $item->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>


                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn bg-gradient-danger" data-dismiss="modal"><i
                                                class="fa fa-times"></i> ยกเลิก</button>
                                        <button type="button" class="btn bg-gradient-success" id="savedata"
                                            value="create"><i class="fa fa-save"></i> บันทึก</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <div class="modal fade" id="modal-import-excel">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Import File Excel</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form class="form-horizontal" id="importForm" method="POST"
                                    enctype="multipart/form-data" action="{{ route('room.importexcel') }}">

                                    @csrf

                                    <input type="hidden" class="form-control" id="user_id" name="user_id"
                                        value="{{ $dataLoginUser['user_id']}}">
                                    <div class="modal-body">

                                        <div class="box-body">
                                            <h5 class="modal-title text-info text-center">คู่มือใช้งาน / ไฟล์ Template</h5>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <a href="{{ url('uploads/vdo/ImportExcel.mp4') }}"
                                                            target="_blank" class="btn  bg-gradient-warning"><i
                                                                class="fas fa-file"></i>
                                                            คู่มือใช้งาน</a>
                                                        <a href="{{ url('uploads/file/template_import.xlsx') }}"
                                                            target="_blank" class="btn  bg-gradient-primary"><i
                                                                class="fas fa-file-excel"></i> Excel Template</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="modal-title text-info text-center">อัพโหลดไฟล์</h5>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="" name="import_file"
                                                                id="import_file" accept=".xlsx, .xls" required>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn bg-gradient-danger" data-dismiss="modal"><i
                                                    class="fa fa-times"></i> ยกเลิก</button>
                                            <button type="submit" class="btn bg-gradient-success"><i
                                                    class="fa fa-save"></i> บันทึก</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <div class="modal fade" id="modal-update-excel">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Import File Excel</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form class="form-horizontal" id="importForm2" method="POST"
                                    enctype="multipart/form-data" action="{{ route('room.updateexcel') }}">

                                    @csrf

                                    <input type="hidden" class="form-control" id="user_id" name="user_id"
                                        value="{{ $dataLoginUser['user_id'] }}">
                                    <div class="modal-body">

                                        <div class="box-body">
                                            <h5 class="modal-title text-info text-center">คู่มือใช้งาน / ไฟล์ Template</h5>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <a href="{{ url('uploads/vdo/updateExcel.mp4') }}"
                                                            target="_blank" class="btn  bg-gradient-warning"><i
                                                                class="fas fa-file"></i>
                                                            คู่มือใช้งาน</a>
                                                        <a href="{{ url('uploads/file/template_update_import.xlsx') }}"
                                                            target="_blank" class="btn  bg-gradient-primary"><i
                                                                class="fas fa-file-excel"></i> Excel Template</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 class="modal-title text-info text-center">อัพโหลดไฟล์</h5>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="" name="import_file2"
                                                                id="import_file2" accept=".xlsx, .xls" required>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn bg-gradient-danger" id="importCanBtn" data-dismiss="modal"><i
                                                    class="fa fa-times"></i> ยกเลิก</button>
                                            <button type="submit" class="btn bg-gradient-success" id="importBtn"><i
                                                    class="fa fa-save"></i> บันทึก</button>
                                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#importForm').submit(function() {
                $('#importBtn').prop('disabled', true);
                $('#importCanBtn').prop('disabled', true);
                $('#importBtn').html('<i class="fas fa-circle-notch fa-spin"></i> รอสักครู่...');
                return true;
            });
        });
    </script>
    <script>
        // Add event listener to the export button
        document.getElementById('export2').addEventListener('click', exportTableToExcel);

        // Function to export the HTML table to an Excel file
        function exportTableToExcel() {
            // Get the HTML table element
            const table = document.getElementById('table');

            // Convert the table to a worksheet
            const worksheet = XLSX.utils.table_to_sheet(table);

            const workbook = XLSX.utils.book_new();

            // Add the worksheet to the workbook
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            // Export the workbook to an Excel file
            XLSX.writeFile(workbook, 'Rooms.xlsx');
        }
    </script>
    <script>
        function showBookingInfo(id) {
            //console.log(id);
            fetch(`/api/rooms/booking/customer/${id}`)
                .then(response => response.json())
                .then(data => {
                    //console.log(data);
                    if (data.length > 0) {
                        let Title = `${data[0].room_address} (${data[0].address})`;
                        let htmlContent =
                            '<table style="width:100%; font-size: 13px;" class="table table-striped table-bordered text-center">';
                        htmlContent += `
                    <tr>
                        <th>#</th>
                        <th width=20%>ชื่อลูกค้า</th>
                        <th>MNG.</th>
                        <th width=15%>สถานะลูกค้า</th>
                        <th width=15%>วันที่เสนอราคา</th>
                        <th width=15%>วันที่จอง</th>
                        <th width=15%>วันที่ทำสัญญา</th>
                        <th width=15%>วันที่โอน</th>
                        <th width=10%>เงินจอง</th>
                        <th width=10%>SLA</th>
                    </tr>`;
                        data.forEach((item, index) => {
                            let phone = "";
                            let bid_date = "";
                            let booking_date = "";
                            let booking_contract = "";
                            let mortgage_date = "";

                            if (item.bid_date) {
                                bid_date = item.bid_date;
                            } else {
                                bid_date = "-";
                            }
                            if (item.booking_date) {
                                booking_date = item.booking_date;
                            } else {
                                booking_date = "-";
                            }
                            if (item.booking_contract) {
                                booking_contract = item.booking_contract;
                            } else {
                                booking_contract = "-";
                            }
                            if (item.mortgage_date) {
                                mortgage_date = item.mortgage_date;
                            } else {
                                mortgage_date = "-";
                            }
                            if (item.customer_tel) {
                                phone = item.customer_tel;
                            } else {
                                phone = "ไม่มีเบอร์";
                            }
                            if (item.deposit) {
                                deposit = item.deposit;
                            } else {
                                deposit = "-";
                            }
                            htmlContent += `<tr style="background-color: ${getBackgroundColor(index)};">
                    <td>${index + 1}</td>
                    <td>คุณ ${item.customer_fname} ${item.customer_sname}
                        <div class="phone-icon">
                            <i class="fa fa-phone cursor-pointer" aria-hidden="true"></i>
                             <span class="phone-number">${phone}</span>
                            </div>
                    </td>
                    <td>${item.team}</td>
                    <td>${item.status}</td>
                    <td>${bid_date}</td>
                    <td>${booking_date}</td>
                    <td>${booking_contract}</td>
                    <td>${mortgage_date}</td>
                    <td>${deposit}</td>
                    <td class="text-danger">${item.daysOverdue} วัน</td>
                  </tr>`;
                        });
                        htmlContent += '</table>';
                        // ใช้ SweetAlert2 เพื่อแสดงข้อมูล
                        Swal.fire({
                            title: 'ข้อมูลการจองห้อง ' + Title,
                            html: htmlContent,
                            icon: 'info',
                            confirmButtonText: 'Close',
                            customClass: 'swal-wide'

                        });

                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Data not found',
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        function showBookingInfoSale(id) {
            //console.log(id);
            fetch(`/api/rooms/booking/customer/${id}`)
                .then(response => response.json())
                .then(data => {
                    //console.log(data);
                    if (data.length > 0) {
                        let Title = `${data[0].room_address} (${data[0].address})`;
                        let htmlContent =
                            '<table style="width:100%; font-size: 13px;" class="table table-striped table-bordered text-center">';
                        htmlContent += `
                    <tr>
                        <th>#</th>


                        <th width=15%>สถานะลูกค้า</th>
                        <th width=15%>วันที่เสนอราคา</th>
                        <th width=15%>วันที่จอง</th>
                        <th width=15%>วันที่ทำสัญญา</th>
                        <th width=15%>วันที่โอน</th>
                        <th width=15%>เงินจอง</th>
                        <th width=15%>SLA</th>
                    </tr>`;
                        data.forEach((item, index) => {
                            let phone = "";
                            let bid_date = "";
                            let booking_date = "";
                            let booking_contract = "";
                            let mortgage_date = "";

                            if (item.bid_date) {
                                bid_date = item.bid_date;
                            } else {
                                bid_date = "-";
                            }
                            if (item.booking_date) {
                                booking_date = item.booking_date;
                            } else {
                                booking_date = "-";
                            }
                            if (item.booking_contract) {
                                booking_contract = item.booking_contract;
                            } else {
                                booking_contract = "-";
                            }
                            if (item.mortgage_date) {
                                mortgage_date = item.mortgage_date;
                            } else {
                                mortgage_date = "-";
                            }
                            if (item.customer_tel) {
                                phone = item.customer_tel;
                            } else {
                                phone = "ไม่มีเบอร์";
                            }
                            if (item.deposit) {
                                deposit = item.deposit;
                            } else {
                                deposit = "-";
                            }
                            htmlContent += `<tr style="background-color: ${getBackgroundColor(index)};">
                    <td>${index + 1}</td>


                    <td>${item.status}</td>
                    <td>${bid_date}</td>
                    <td>${booking_date}</td>
                    <td>${booking_contract}</td>
                    <td>${mortgage_date}</td>
                    <td>${deposit}</td>
                    <td class="text-danger">${item.daysOverdue} วัน</td>
                  </tr>`;
                        });
                        htmlContent += '</table>';
                        // ใช้ SweetAlert2 เพื่อแสดงข้อมูล
                        Swal.fire({
                            title: 'ข้อมูลการจองห้อง ' + Title,
                            html: htmlContent,
                            icon: 'info',
                            confirmButtonText: 'Close',
                            customClass: 'swal-wide'

                        });

                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Data not found',
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        function getBackgroundColor(index) {
            const colors = ['#E1F5FE', '#B3E5FC'];
            return colors[index % colors.length];
        }
    </script>


    @if (count($errors) > 0)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Excel Error กรอกข้อมูลไม่ครบ',
                html: '@foreach ($errors->all() as $error)<p class="text-danger" style="font-size: 14px;">{{ $error }}</p>@endforeach',
            });
        </script>
    @endif

    <!-- Del ALL-->
    <script>
        $(document).ready(function() {
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
                var selectedRooms = [];

                // หา checkbox ที่ถูกเลือกและเก็บค่า data-id ลงใน selectedRooms
                $('.room-checkbox:checked').each(function() {
                    selectedRooms.push($(this).data('id'));
                });

                // ตรวจสอบว่ามีรายการถูกเลือกหรือไม่
                if (selectedRooms.length === 0) {
                    // ถ้าไม่มีรายการถูกเลือก ให้แสดง SweetAlert แจ้งเตือน
                    Swal.fire({
                        icon: 'info',
                        title: 'ไม่มีรายการที่เลือก',
                        text: 'กรุณาเลือกรายการที่ต้องการลบ/คืนห้อง',
                    });
                } else {
                    // ถ้ามีรายการถูกเลือก ให้แสดง SweetAlert เพื่อยืนยันการลบ
                    Swal.fire({
                        title: 'คุณแน่ใจไหม?',
                        text: 'หากต้องการ ลบ/คืนห้อง รายการที่เลือก โปรดยืนยันการลบ',
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
                                url: '{{ route('room.delete.selected') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    selected_rooms: selectedRooms
                                },
                                success: function(data) {
                                    // ตรวจสอบคำตอบจากเซิร์ฟเวอร์ และแสดง SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลบ/คืนห้องสำเร็จ!',
                                        showConfirmButton: true,
                                        timer: 2500
                                    });
                                    setTimeout(
                                        "location.href = '{{ route('room.search') }}';",
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
                var selectedRooms = [];

                // หา checkbox ที่ถูกเลือกและเก็บค่า data-id ลงใน selectedRooms
                $('.room-checkbox:checked').each(function() {
                    selectedRooms.push($(this).data('id'));
                });

                // ตรวจสอบว่ามีรายการถูกเลือกหรือไม่
                if (selectedRooms.length === 0) {
                    // ถ้าไม่มีรายการถูกเลือก ให้แสดง SweetAlert แจ้งเตือน
                    Swal.fire({
                        icon: 'info',
                        title: 'ไม่มีรายการที่เลือก',
                        text: 'กรุณาเลือกรายการที่ต้องการ ลบ/คืนห้อง',
                    });
                } else {
                    // ถ้ามีรายการถูกเลือก ให้แสดง SweetAlert เพื่อยืนยันการลบ
                    Swal.fire({
                        title: 'คุณแน่ใจไหม?',
                        text: 'หากต้องการ ลบ/คืนห้อง โปรดยืนยัน',
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
                                url: '{{ route('room.cancel.selected') }}',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    selected_rooms: selectedRooms
                                },
                                success: function(data) {
                                    // ตรวจสอบคำตอบจากเซิร์ฟเวอร์ และแสดง SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลบ/คืนห้องสำเร็จ!',
                                        showConfirmButton: true,
                                        timer: 2500
                                    });
                                    setTimeout(
                                        "location.href = '{{ route('room') }}';",
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


        });
    </script>


    <!-- Check&UnCheck ALL-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleButton = document.getElementById('toggle_check');
            var checkboxes = document.querySelectorAll('.status');
            var isUnchecked = Array.from(checkboxes).every(checkbox => !checkbox.checked);
            toggleButton.textContent = isUnchecked ? 'Check all' : 'Uncheck all';

            toggleButton.addEventListener('click', function() {
                isUnchecked = Array.from(checkboxes).every(checkbox => !checkbox.checked);
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = isUnchecked;
                });
                toggleButton.textContent = isUnchecked ? 'Uncheck all' : 'Check all';
            });
        });
    </script>

    <!-- Error Project Selected -->
    <script>
        // document.getElementById('searchForm').addEventListener('submit', function(event) {
        //     const project_id = document.getElementById('project_id').value;
        //     const projectSelect = document.getElementById('project_id');


        //     if (!project_id) {
        //         event.preventDefault();
        //         projectSelect.classList.add('is-invalid');
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'เกิดข้อผิดพลาด',
        //             text: 'กรุณาเลือกโครงการ',
        //         });
        //     } else {

        //         projectSelect.classList.remove('is-invalid');
        //     }

        // });
        document.getElementById('importForm').addEventListener('submit', function(event) {
            const import_file = document.getElementById('import_file').value;

            if (!import_file) {
                event.preventDefault();
                const importFileInput = document.getElementById('import_file');
                importFileInput.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาเลือกไฟล์ Excel',
                });
            }

        });
        document.getElementById('importForm2').addEventListener('submit', function(event) {
            const import_file2 = document.getElementById('import_file2').value;

            if (!import_file2) {
                event.preventDefault();
                const importFileInput2 = document.getElementById('import_file2');
                importFileInput2.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาเลือกไฟล์ Excel',
                });
            }

        });
    </script>


    <!-- Return Form-->
    @if (isset($formInputs))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var formInputs = @json($formInputs);

                Object.keys(formInputs).forEach(function(key) {
                    var input = document.querySelector('[name="' + key + '"]');
                    if (input) {
                        input.value = formInputs[key];
                    }
                });
            });
        </script>
    @endif


    <!-- CRUD -->
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#table').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': false,
                'autoWidth': false,
                "responsive": true,
                "lengthMenu": [ [ 25, 50, -1], [ 25, 50, "All"] ]
            });


            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // รูปแบบวันที่
                autoclose: true,
            });


            $('#startdate').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#enddate').datepicker('setStartDate', selectedStartDate);
            });

            $('#projectSelect').on('change', function() {
                var projectId = $(this).val();
                //console.log(projectId);
                if (projectId) {
                    $.ajax({
                        url: '/getplans/' + projectId,
                        type: 'GET',
                        success: function(data) {
                            //console.log(data.plans);
                            var plan = data.plans;
                            var options = '<option value="">เลือก Plan</option>';
                            for (let i = 0; i < plan.length; i++) {
                                options += '<option value="' + plan[i].id + '">' + plan[i]
                                    .name + '</option>';
                            }
                            $('#planSelect').html(options);

                        }
                    });
                }
            });

            //Create modal
            $('#Create').click(function() {
                $('#savedata').val("create");
                $('#createForm').trigger("reset");
                $('#modal-create').modal('show');
            });

            $('#ImportExcel').click(function() {
                $('#modal-import-excel').modal('show');
            });

            //Save
            $('#savedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const user_id = $("#user_id").val();
                const project_id = $("#project_id").val();
                const room_type = $("#room_type").val();
                const plan_id = $("#plan_id").val();
                const floor = $("#floor").val();
                const building = $("#building").val();
                const room_address = $("#room_address").val();
                const address = $("#address").val();
                const direction = $("#direction").val();
                const key = $("#key").val();
                const keycard = $("#keycard").val();
                const price = $("#price").val();
                const area = $("#area").val();
                const furnitureValues = $("input[name='furniture[]']:checked").map(function() {
                    return $(this).val();
                }).get();

                const facilityValues = $("input[name='facility[]']:checked").map(function() {
                    return $(this).val();
                }).get();

                const formData = new FormData($('#createForm')[0]);

                // formData.forEach((value, key) => {
                // console.log(key, value);
                // });

                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('room.store') }}",
                    type: "POST",
                    dataType: "json",

                    success: function(data) {
                        //console.log(data);
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
                                // setTimeout("location.href = '{{ route('room') }}';",
                                //     1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedata').html('ลองอีกครั้ง');
                                $('#createForm').trigger("reset");
                                $('.project_id').text(data.error.project_id);
                                $('.plan_id').text(data.error.plan_id);
                                $('.room_type').text(data.error.room_type);
                                $('.floor').text(data.error.floor);
                                $('.building').text(data.error.building);
                                $('.room_address').text(data.error.room_address);
                                $('.address').text(data.error.address);
                                $('.direction').text(data.error.direction);
                                $('.key').text(data.error.key);
                                $('.keycard').text(data.error.keycard);
                                $('.price').text(data.error.price);
                                $('.area').text(data.error.area);
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
                            $('.room_address_err').text("ซ้ำ");
                            Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'ห้องเลขที่ ซ้ำ',
                                showConfirmButton: true,
                                timer: 1500
                            });
                        }
                    }
                });
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
