@extends('layouts.app')

@section('title', 'ห้อง')

@section('content')
<form id="editForm" name="editForm"  method="post" action="{{route('room.update.partner')}}">
    @csrf
    <input type="hidden" name="user_id" value="{{$dataLoginUser['user_id']}}">
    <input type="hidden" name="room_id" value="{{$rooms->id}}">
    <div class="content-header">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-9">

                    <h1 class="m-0">
                        ห้อง {{ $rooms->room_address }} ( {{ $rooms->address }} )
                        {{-- {{ $rooms->user_name ? ' : ' . $rooms->user_name : '' }} --}}
                        <a href="{{route('room')}}" class="btn bg-gradient-primary " type="button">
                            <i class="fa-solid fa fa-reply"></i> กลับ </a>

                            {{-- @if ($message!="")
                            <a href="" class="btn btn-danger col-4">
                                <i class="icon fas fa-ban"></i>Alert !! {{$message}}</a>
                            @endif --}}



                    </h1>


                </div><!-- /.col -->

                <div class="col-md-3">
                    <button class="btn bg-gradient-success col-md-12" type="submit"><i class="fa-solid fa-arrow-up-from-bracket"></i> อัพเดทข้อมูล</button>
                </div>
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


                </div>
                <!-- สถานะ -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="status_id">สถานะงาน</label>
                                    <select class="form-control" id="status_work" name="status_work">

                                        <option value="" {{ ( $rooms->status_work == null) ? 'selected' : '' }}>เลือก</option>
                                        <option value="Prebook" {{ ( $rooms->status_work == "Prebook") ? 'selected' : '' }}>Prebook</option>
                                        <option value="Booking" {{ ( $rooms->status_work == "Booking") ? 'selected' : '' }}>Booking</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="price">เงินจอง (บาท)</label>
                                    <input class="form-control" name="booking_money_singer" type="number"
                                        value="{{ $rooms->booking_money_singer }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="area">ชื่อ Sale</label>
                                    <input type="text" class="form-control" name="sale_name_singer"
                                        value="{{ $rooms->sale_name_singer }}">
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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


        });
    </script>

     <!-- Check Emty Search-->
     <script>
        document.getElementById('editForm').addEventListener('submit', function(event) {
            const status_work = document.getElementById('status_work').value;
            const workSelect = document.getElementById('status_work');


            if (!status_work) {
                event.preventDefault();
                workSelect.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาเลือกสถานะงาน',
                });
            } else {

                workSelect.classList.remove('is-invalid');
            }

        });
    </script>








@endpush
