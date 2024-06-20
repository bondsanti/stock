@extends('layouts.app')

@section('title', 'ห้อง')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
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
                        <div class="card-header card-outline card-info">
                            <h3 class="card-title">ค้นหา ห้อง</h3>

                        </div>
                        <form action="{{ route('room.search') }}" method="post" id="searchForm">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-sm-4">

                                        <div class="form-group">
                                            <label>โครงการ</label>
                                            <select name="project_id" id="project_id" class="form-control">
                                                <option value="ทั้งหมด">ทั้งหมด</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>ห้องเลขที่</label>
                                            <input class="form-control" name="room_address" type="text" value=""
                                                placeholder="ห้องเลขที่">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>บ้านเลขที่</label>
                                            <input class="form-control" name="address" type="text" value=""
                                                placeholder="บ้านเลขที่">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>ชื่อลูกค้า</label>
                                            <input class="form-control" name="user_name" type="text" value=""
                                                placeholder="ชื่อลูกค้า">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>เบอร์โทรศัพท์</label>
                                            <input class="form-control" name="phone" type="text" value=""
                                                placeholder="เบอร์โทรศัพท์">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>MNG</label>
                                            <input class="form-control" name="agent" type="text" value=""
                                                placeholder="MNG">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>ช่องทางการขาย</label>
                                            <input class="form-control" name="fixseller" type="text" value=""
                                                placeholder="ช่องทางการขาย">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>ราคาเริ่มต้น</label>
                                            <input class="form-control" name="startprice" type="number" value=""
                                                placeholder="ราคาเริ่มต้น">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="ถึงช่วงราคา-group">
                                            <label>ราคาสิ้นสุด</label>
                                            <input class="form-control" name="endprice" type="number" value=""
                                                placeholder="ถึงช่วงราคา">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>เลือกประเภทวันที่</label>
                                            <select name="dateselect" id="dateselect" class="form-control">
                                                <option value="bid_date">วันที่เสนอราคา</option>
                                                <option value="mortgaged_date">วันที่โอน</option>
                                                <option value="booking_date">วันที่จอง</option>
                                                <option value="contract_date">วันที่ทำสัญญา</option>
                                                <option value="" selected>ทั้งหมด</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>วันที่เริ่มต้น</label>
                                            <input class="form-control datepicker" name="startdate" id="startdate"
                                                type="text" value="" placeholder="วันที่เริ่มต้น">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="ถึงช่วงราคา-group">
                                            <label>ถึงวันที่</label>
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

                    <div class="row">
                        <div class="col-12">

                            {{-- <h4 class="text-center mt-2"></h4> --}}
                            <button type="button" class="btn btn-info btn-block">
                                <h4 class="mt-2"><i class="fa fa-exclamation"></i> กรุณา ค้นหา ห้องที่คุณต้องการ</h4>
                            </button>

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
                                <form id="createForm" name="createForm" class="form-horizontal" method="post">
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
                                        value="{{ $dataLoginUser['user_id'] }}">
                                    <div class="modal-body">

                                        <div class="box-body">
                                            <h5 class="modal-title text-info text-center">คู่มือใช้งาน / ไฟล์ Template</h5>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <a href="{{ url('uploads/vdo/ImportExcel.mp4') }}"
                                                            target="_blank" class="btn bg-gradient-warning">
                                                            <i class="fas fa-file"></i> คู่มือใช้งาน
                                                        </a>
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
                                                                id="import_file" accept=".xlsx, .xls">

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn bg-gradient-danger" id="importCanBtn"
                                                data-dismiss="modal"><i class="fa fa-times"></i> ยกเลิก</button>
                                            <button type="submit" class="btn bg-gradient-success" id="importBtn"><i
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
                                                        <a href="{{ url('uploads/vdo/ImportExcel.mp4') }}"
                                                            target="_blank" class="btn bg-gradient-warning">
                                                            <i class="fas fa-file"></i> คู่มือใช้งาน
                                                        </a>

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
                                                                id="import_file2" accept=".xlsx, .xls">

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
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->






                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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


    @if (count($errors) > 0)
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Excel Error กรอกข้อมูลไม่ครบ',
                html: '@foreach ($errors->all() as $error)<p class="text-danger" style="font-size: 14px;">{{ $error }}</p>@endforeach',
            });
        </script>
    @endif


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
                "responsive": true
                // "buttons": ["excel"],
                // 'language': {
                //     'buttons': {
                //         'excel': 'Export to Excel'
                //     }
                // }
            });
            // $('#exportBtn').on('click', function() {
            //     $('#table').DataTable().button('.buttons-excel').trigger();
            // });

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
                        url: 'getplans/' + projectId,
                        type: 'GET',
                        success: function(data) {
                            console.log(data.plans);
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
                                setTimeout("location.href = '{{ route('room') }}';",
                                    1500);

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
