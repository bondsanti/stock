@extends('layouts.app')

@section('title', 'แดชบอร์ด')

@section('content')

    @push('style')
        <style>


            #table thead th {
                font-size: 13px;
            }

            #table tbody td {
                font-size: 13px;
                /* text-align: center; */
                vertical-align: middle;
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
                <div class="col-sm-6">
                    <h1 class="m-0">แดชบอร์ด</h1>
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


                    <div class="row">

                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($all) }}</h3>

                                        <p>ห้องทั้งหมด</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-building"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($empty) }}</h3>
                                        <p>ห้องว่าง</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-home"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($bid) }}</h3>

                                        <p>ออกใบเสนอราคา</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->

                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($bookings) }}</h3>

                                        <p>จองแล้ว</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-calendar-check"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($contract) }}</h3>

                                        <p>ห้องทำสัญญา</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ number_format($mortgage) }}</h3>

                                        <p>ห้องโอน</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-circle"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                    </div>
                </div>

            </div>

            <!--Table-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title text-danger">ห้องเกินกำหนด {{ $totalExp }} รายการ</h3>

                        </div>

                        <div class="card-body table-responsive">
                            <table id="table" class="table table-sm text-center table-striped ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="7%">โครงการ</th>
                                        <th width="5%">ห้องเลขที่</th>
                                        <th>บ้านเลขที่</th>
                                        <th>Type</th>
                                        <th>ขนาด<sup>(ตรม.)</sup></th>
                                        @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                            <th>ลูกค้า</th>
                                        @endif
                                        <th>สถานะ</th>
                                        <th>วันที่เสนอราคา</th>
                                        <th>วันจอง</th>
                                        <th width="7%">วันที่ทำสัญญา</th>
                                        @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                        <th>หมายเหตุ</th>
                                        @endif
                                        {{-- <th>ผ่านมาแล้ว<sup>(วัน)</sup></th> --}}
                                        @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                            <th>MNG.</th>
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                        $previousRoomNumber = null;
                                        $rowColor = 'background-color: #fff;'; // Default color row แรก
                                    @endphp

                                    @foreach ($expList as $projectName => $rooms)
                                        @foreach ($rooms as $roomNumber => $roomBookings)
                                            @foreach ($roomBookings as $booking)
                                                @php
                                                    //เช็คเลขห้อง
                                                    if ($booking->room_address == $previousRoomNumber) {
                                                    } else {
                                                        $rowColor = $rowColor == 'background-color: #fff;' ? 'background-color: rgba(0,0,0,.05);' : 'background-color: #fff;';
                                                        $previousRoomNumber = $booking->room_address;
                                                    }
                                                @endphp

                                                <tr style="{{ $rowColor }}">
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ Str::limit($projectName, 15) }}</td>
                                                    <td>{{ $booking->room_address }}</td>
                                                    <td>{{ $booking->address }}</td>
                                                    <td>{{ Str::limit($booking->type,15) }}</td>
                                                    <td>{{ $booking->area }}</td>
                                                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                    <td>{{ $booking->customer_fname }} {{ $booking->customer_sname }}</td>
                                                    @endif
                                                    <td>
                                                        @if ($booking->status == 'จองแล้ว')
                                                        <span id="badge" class="badge rounded-pill bg-success">{{ $booking->status }}</span>
                                                        @elseif ($booking->status == 'ทำสัญญา')
                                                        <span id="badge" class="badge rounded-pill bg-primary">{{ $booking->status }}</span>
                                                        @else
                                                        <span id="badge" class="badge rounded-pill bg-warning">{{ $booking->status }}</span>
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
                                                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                    <td>{{ $booking->remark ? $booking->remark : '-' }}</td>
                                                    @endif
                                                    {{-- <td class="text-danger">{{ $booking->daysOverdue }} วัน</td> --}}
                                                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->role_type == 'Staff')
                                                        <td>{{ $booking->team ? $booking->team : '-' }}</td>
                                                        <td>

                                                            <button class="btn btn-xs bg-gradient-danger data-cancel"
                                                                data-id="{{ $booking->id }}"
                                                                data-room-id="{{ $booking->rooms_id }}">ยกเลิก</button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // $('#table').DataTable({
            //     'paging': false,
            //     'lengthChange': false,
            //     'searching': false,
            //     'ordering': false,
            //     'info': false,
            //     'autoWidth': false,
            //     "responsive": true,
            // });


            const user_id = {{ $dataLoginUser['user_id'] }};
            //ยกเลิกการจอง
            $('body').on('click', '.data-cancel', function() {
                const id = $(this).data("id");
                const roomId = $(this).data("roomId");
                //console.log(roomId);
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
                            url: '/api/rooms/booking/cancel/' + id + '/' + user_id + '/' +
                                roomId,
                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'ยกเลิก การจองสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout(
                                    "location.href = '{{ route('main') }}';",
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






        });
    </script>
@endpush
