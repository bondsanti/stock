@extends('layouts.app')

@section('title', 'ห้อง')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">ห้อง ทั้งหมด
                        <a type="button" class="btn bg-gradient-primary" href="{{ route('room') }}">
                            <i class="fa fa-reply"></i> กลับ
                        </a>

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
                        <form action="{{ route('room.search.partner') }}" method="post">
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

                                        <label for="" class="col-md-12 col-form-label ">ราคาเริ่มต้น</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="startprice" type="text" value=""
                                                placeholder="ราคาเริ่มต้น">
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
                                        <label for="" class="col-md-12 col-form-label ">ถึงช่วงราคา</label>
                                        <div class="col-md-12 my-auto">
                                            <input class="form-control" name="endprice" type="text" value=""
                                                placeholder="ถึงช่วงราคา">
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
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="mt-2"></div>
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
                                                       {{ in_array($item->id, request('status', [])) ? 'checked' : '' }}>
                                                <label for="{{ $item->id }}">{{ $item->name }}</label>
                                            @endforeach
                                            <input type="checkbox" name="status_pi" class="status" id="status_pi"
                                                   value="{{ $isRole->dept }}" {{ request('status_pi') === $isRole->dept ? 'checked' : '' }}>
                                            <label for="status_pi" class="mr-2">{{ $isRole->dept }}</label>
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


                                    </h3>
                                </div>

                                <div class="card-body table-responsive">


                                    <table id="table" class="table table-hover table-striped text-nowrap">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>โครงการ</th>
                                                <th>ห้องเลขที่</th>
                                                <th>บ้านเลขที่</th>
                                                <th>รายละเอียดอาคาร</th>
                                                <th>ขนาด<sup>(ตรม.)</sup></th>
                                                <th>ราคา</th>
                                                <th>ราคา<sup>(Special)</sup></th>
                                                <th>สถานะ</th>
                                                <th>วันจอง</th>
                                                <th>วันโอน</th>
                                                <th>วันที่ทำสัญญา</th>
                                                {{-- <th>MNG.</th> --}}
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rooms as $room)
                                            @php
                                                $isPrebook = $room->status_work === 'Prebook';
                                                $isBooking = $room->status_work === 'Booking';
                                            @endphp
                                            <tr class="text-center" @if ($isPrebook) style="background-color: #fef08a;" @elseif ($isBooking) style="background-color: #22c55e;" @endif>
                                                <td width="5%">{{ $loop->index + 1 }}</td>
                                                <td>{{ optional($room->project)->name }}</td>
                                                <td>{{ $room->room_address }}</td>
                                                <td>{{ $room->address }}</td>
                                                <td>{{ 'อาคาร ' . optional($room->plan)->name . ', ตึก ' . $room->building . ', ชั้น ' . $room->floor }}</td>
                                                <td>{{ number_format($room->area, 2) }}</td>
                                                <td>{{ number_format($room->price) }}</td>
                                                <td>{{ number_format($room->special_price_singer) }}</td>
                                                <td>{{ optional($room->status)->name }}</td>
                                                <td>
                                                    @empty(!$room->booking_date)
                                                    {{ date('d/m/Y', strtotime($room->booking_date)) }}
                                                    @else
                                                    -
                                                    @endempty
                                                </td>
                                                <td>
                                                    @empty(!$room->mortgaged_date)
                                                    {{ date('d/m/Y', strtotime($room->mortgaged_date)) }}
                                                    @else
                                                    -
                                                    @endempty
                                                </td>
                                                <td>
                                                    @empty(!$room->contract_date)
                                                    {{ date('d/m/Y', strtotime($room->contract_date)) }}
                                                    @else
                                                    -
                                                    @endempty
                                                </td>
                                                {{-- <td>
                                                    @if (strtolower($room->agent) === strtolower($isRole->dept))
                                                    {{ $room->agent }}
                                                    @else
                                                    -
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if ($room->booking && optional($room->booking)->team && strtolower($room->booking->team) === strtolower($isRole->dept))
                                                    <a href="{{ url('/rooms/partner/edit/'.$room->id) }}" class="btn bg-gradient-info btn-sm edit-item"
                                                        data-toggle="tooltip" data-placement="top" title="แก้ไข">
                                                        <i class="fa fa-pencil-square"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>
                    <br>





                </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>

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
                'ordering': false,
                'info': false,
                'autoWidth': false,
                "responsive": true,
                'lengthMenu': [25, 50, 100],

            });


            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // รูปแบบวันที่
                autoclose: true,
            });



            $('#startdate').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#enddate').datepicker('setStartDate', selectedStartDate);
            });



        });
    </script>


    <!-- Check&UnCheck ALL-->
    <script>
        var toggleButton = document.getElementById('toggle_check');
        var checkboxes = document.querySelectorAll('.status');
        var isUnchecked = true;

        toggleButton.addEventListener('click', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = isUnchecked;
            });

            if (isUnchecked) {
                toggleButton.textContent = 'Uncheck all';

            } else {

                toggleButton.textContent = 'Check all';
            }

            isUnchecked = !isUnchecked;
        });
    </script>


    <!-- Check Emty Search-->
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            const project_id = document.getElementById('project_id').value;
            const projectSelect = document.getElementById('project_id');


            if (!project_id) {
                event.preventDefault();
                projectSelect.classList.add('is-invalid');
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาเลือกโครงการ',
                });
            } else {

                projectSelect.classList.remove('is-invalid');
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




@endpush
