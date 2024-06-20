@extends('layouts.app')

@section('title', 'ผู้ใช้งานระบบ')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">ผู้ใช้งานระบบ ทั้งหมด
                        <button type="button" class="btn bg-gradient-primary" id="Create">
                            <i class="fa fa-plus"></i> เพิ่ม User
                        </button>
                        <button type="button" class="btn bg-gradient-primary" id="CreatePartner">
                            <i class="fa fa-plus"></i> เพิ่ม Partner
                        </button>
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
                <div class="col-sm-2">
                    <div class="small-box bg-gradient-info">
                        <div class="inner">
                            <h3>{{ $countUser }}</h3>
                            <p>ข้อมูลผู้ใช้งานทั้งหมด</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="small-box bg-gradient-dark">
                        <div class="inner">
                            <h3>{{ $countUserPartner }}</h3>
                            <p>ผู้ใช้งาน [ Partner ]</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="small-box bg-gradient-success">
                        <div class="inner">
                            <h3>{{ $countUserSPAdmin + $countUserAdmin }}</h3>
                            <p>ผู้ใช้งาน [ SuperAdmin&Admin ]</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="small-box bg-gradient-pink">
                        <div class="inner">
                            <h3>{{ $countUserStaff }}</h3>
                            <p>ผู้ใช้งาน [ Staff ]</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="small-box bg-gradient-primary">
                        <div class="inner">
                            <h3>{{ $countUserSale }}</h3>
                            <p>ผู้ใช้งาน [ Sale ]</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="small-box bg-gradient-purple">
                        <div class="inner">
                            <h3>{{ $countUsers }}</h3>
                            <p>ผู้ใช้งาน [ User ]</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!--Table-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ผู้ใช้งานระบบ</h3>

                        </div>

                        <div class="card-body table-responsive">
                            <table id="table" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>รหัสพนักงาน</th>
                                        <th>อีเมล์</th>
                                       {{-- <th>Role id Ref Position</th> --}}
                                        <th>Dept</th>
                                        <th>ประเภทผู้ใช้งาน</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        {{-- @if ($user->role->active == '1') --}}
                                        <tr>
                                            <td width="5%">{{ $loop->index + 1 }}</td>
                                            <td>
                                                @if ($user->api_data)
                                                    {{ $user->api_data['code'] }}
                                                @else
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->api_data)
                                                    {{ $user->api_data['email'] }}
                                                @else
                                                @endif
                                            </td>
                                            {{-- <td>{{ $user->role->code }}</td>
                                                <td>{{ $user->role->name_th }}</td>
                                                <td width="30%">{{ optional($user->position)->name }}</td> --}}
                                            <td>{{ $user->dept }}</td>
                                            <td>

                                                {{ $user->role_type }}


                                            </td>

                                            {{-- <td>{{ $user->active == '0' ? 'Disable' : 'Enable' }}</td> --}}
                                            <td width="15%" class="text-center">
                                                @if ($dataLoginUser['user_id'] != $user->user_id)
                                                    <button class="btn bg-gradient-info btn-sm edit-item"
                                                        data-id="{{ $user->id }}" title="แก้ไข">
                                                        <i class="fa fa-pencil-square">
                                                        </i>

                                                    </button>
                                                    <button class="btn bg-gradient-danger btn-sm delete-item"
                                                        data-id="{{ $user->id }}" title="ลบ">
                                                        <i class="fa fa-trash">
                                                        </i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- @endif --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- modal เพิ่มข้อมูลพนักงาน-->
            <div class="modal fade" id="modal-create">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">เพิ่มข้อมูล ผู้ใช้งานระบบ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="createForm" name="createForm" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{ $dataLoginUser['user_id'] }}">
                            <div class="modal-body">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">รหัสผู้ใช้งานระบบ *Ref
                                                HR</label>
                                            <input type="text" class="col-md-12 form-control" id="code"
                                                name="code" placeholder="" autocomplete="off">
                                            <p class="text-danger mt-1 code_err"></p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="type" class="col-form-label">ประเภทผู้ใช้งาน</label>
                                            <select class="col-md-12 form-control" id="role_type" name="role_type"
                                                required>
                                                <option value="">เลือก</option>
                                                <option value="User">User</option>
                                                <option value="Sale">Sale</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Admin">Admin</option>
                                                <option value="SuperAdmin">SuperAdmin</option>
                                                <option value="Partner">Partner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="selectUser" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">กำหนด Role
                                                <sup>Department</sup></label>
                                            <select class="col-md-12 form-control" id="dept" name="dept">
                                                <option value="">เลือก</option>
                                                <option value="Marketing">Marketing</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Audit">Audit</option>
                                                <option value="Legal">Legal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="selectPartner" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">ระบุชื่อ Partner</label>
                                            <input type="text" class="col-md-12 form-control" id="dept2"
                                                name="dept2" placeholder="" autocomplete="off">

                                        </div>
                                    </div>
                                    <div class="form-group" id="selectPartner2" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">Logo Partner</label>
                                            <input type="file" class="" id="logo" name="logo"
                                                placeholder="" autocomplete="off">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn bg-gradient-danger" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>
                                <button type="button" class="btn bg-gradient-success" id="savedata" value="create"><i
                                        class="fa fa-save"></i> บันทึก</button>
                            </div>

                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <!-- modal Edit -->
            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">แก้ไข ข้อมูล</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editForm" name="editForm" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="id_edit" id="id_edit">
                            <input type="hidden" name="user_id" id="user_id"
                                value="{{ $dataLoginUser['user_id'] }}">
                            @csrf
                            <div class="modal-body">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-md-12">

                                            <label for="code" class="col-form-label">รหัสพนักงาน</label>
                                            <input type="text" class="form-control" id="code_edit" name="code_edit"
                                                placeholder="" autocomplete="off" disabled>
                                            <p class="text-danger mt-1 code_edit_err"></p>

                                            <label for="code" class="col-form-label">ชื่อ-สกุล</label>
                                            <input type="text" class="form-control" id="name_edit" name="name_edit"
                                                placeholder="" autocomplete="off" disabled>
                                            <p class="text-danger mt-1 name_edit_err"></p>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="type" class="col-form-label">ประเภทผู้ใช้งาน</label>
                                            <select class="form-control" id="role_type_edit" name="role_type_edit">
                                                <option value="">เลือก</option>
                                                <option value="User">User</option>
                                                <option value="Sale">Sale</option>
                                                <option value="Staff">Staff</option>
                                                <option value="Admin">Admin</option>
                                                <option value="SuperAdmin">SuperAdmin</option>
                                                <option value="Partner">Partner</option>
                                            </select>

                                            {{-- <fieldset>
                                                <label for="type" class="col-form-label">สถานะ</label>
                                                <div class="col-sm-6">
                                                    <div style="margin-top:10px">
                                                        <label>
                                                            <input type="radio" id="r1" name="r1"
                                                                value="0" class="minimal">
                                                            Enable
                                                        </label>
                                                        <label>
                                                            <input type="radio" id="r1" name="r1"
                                                                value="1" class="minimal">
                                                            Disable
                                                        </label>
                                                    </div>

                                                </div>
                                            </fieldset> --}}
                                        </div>
                                    </div>
                                    <div class="form-group" id="selectUserE" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">กำหนด Role
                                                <sup>Department</sup></label>
                                            <select class="col-md-12 form-control" id="dept_edit" name="dept_edit">
                                                <option value="">เลือก</option>
                                                <option value="Marketing">Marketing</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Audit">Audit</option>
                                                <option value="Legal">Legal</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="selectPartnerE2" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">ระบุชื่อ Partner</label>
                                            <input type="text" class="col-md-12 form-control" id="dept_edit2"
                                                name="dept_edit2" placeholder="" autocomplete="off">

                                        </div>
                                    </div>

                                    <div class="form-group" id="selectPartnerE" style="display: none;">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">Logo Partner</label>
                                            <input type="file" class="" id="logo_edit" name="logo_edit"
                                                placeholder="" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {{-- <label for="inputEmail3" class="col-form-label">Logo Old</label> --}}
                                        <img src="" id="logo_img" class="img-fluid">
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn bg-gradient-danger" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>
                                <button type="button" class="btn bg-gradient-success" id="updatedata" value="update"><i
                                        class="fa fa-save"></i> อัพเดท</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal edit -->


            <!-- modal เพิ่มข้อมูลพนักงาน-->
            <div class="modal fade" id="modal-createpartner">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">เพิ่มข้อมูล Partner</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="createFormPartner" name="createFormPartner" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id"
                                value="{{ $dataLoginUser['user_id'] }}">
                            <div class="modal-body">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">Username</label>
                                            <input type="text" class="col-md-12 form-control" id="username"
                                                name="username" placeholder="" autocomplete="off">
                                            <p class="text-danger mt-1 username_err"></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">ชื่อ-สกุล</label>
                                            <input type="text" class="col-md-12 form-control" id="name_th"
                                                name="name_th" placeholder="" autocomplete="off">
                                            <p class="text-danger mt-1 name_th_err"></p>
                                        </div>
                                    </div>

                                    <div class="form-group" id="">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">ระบุชื่อ Partner
                                                <sup>*เพื่อนำไป Filter</sup></label>
                                            <input type="text" class="col-md-12 form-control" id="deptRole"
                                                name="deptRole" placeholder="" autocomplete="off">
                                            <p class="text-danger mt-1 deptRole_err"></p>
                                        </div>
                                    </div>
                                    <div class="form-group" id="">
                                        <div class="col-md-12">
                                            <label for="inputEmail3" class="col-form-label">Logo Partner</label>
                                            <input type="file" class="" id="logoPartner" name="logoPartner"
                                                placeholder="" autocomplete="off">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn bg-gradient-danger" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>
                                <button type="button" class="btn bg-gradient-success" id="savedata_partner"
                                    value="create"><i class="fa fa-save"></i> บันทึก</button>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function handleRoleChange() {
            var roleSelect = document.getElementById("role_type");
            var selectedRole = roleSelect.value;

            var selectPartnerDiv = document.getElementById("selectPartner");
            var selectPartnerDiv2 = document.getElementById("selectPartner2");
            var selectUserDiv = document.getElementById("selectUser");

            // เช็คว่าค่าที่ถูกเลือกใน dropdown เป็น "Partner" หรือไม่
            if (selectedRole === "Partner") {
                selectPartnerDiv.style.display = "block"; // แสดง <div>
                selectPartnerDiv2.style.display = "block"; // แสดง <div>
            } else if (selectedRole === "User") {
                selectUserDiv.style.display = "block"; // แสดง <div>
            } else {
                selectPartnerDiv.style.display = "none"; // ซ่อน <div>
                selectPartnerDiv2.style.display = "none"; // ซ่อน <div>
                selectUserDiv.style.display = "none"; // ซ่อน <div>
            }
        }

        // เรียกใช้งานฟังก์ชัน handleRoleChange เมื่อมีการเปลี่ยนแปลงใน <select>
        document.getElementById("role_type").addEventListener("change", handleRoleChange);
    </script>



    <script>
        function handleRoleChange() {
            var roleSelect = document.getElementById("role_type_edit");
            var selectedRole = roleSelect.value;

            var selectPartnerDiv = document.getElementById("selectPartnerE");
            var selectPartnerDiv2 = document.getElementById("selectPartnerE2");
            var selectUserDiv = document.getElementById("selectUserE");

            // เช็คว่าค่าที่ถูกเลือกใน dropdown เป็น "Partner" หรือไม่
            if (selectedRole === "Partner") {
                selectPartnerDiv.style.display = "block"; // แสดง <div>
                selectPartnerDiv2.style.display = "block"; // แสดง <div>
            } else if (selectedRole === "User") {
                selectUserDiv.style.display = "block"; // แสดง <div>
            } else {
                selectPartnerDiv.style.display = "none"; // ซ่อน <div>
                selectPartnerDiv2.style.display = "none"; // ซ่อน <div>
                selectUserDiv.style.display = "none";
            }
        }

        // เรียกใช้งานฟังก์ชัน handleRoleChange เมื่อมีการเปลี่ยนแปลงใน <select>
        document.getElementById("role_type_edit").addEventListener("change", handleRoleChange);
    </script>


    <script>
        $(document).ready(function() {


            $('#table').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                "responsive": true
            });

            const user_id = {{ $dataLoginUser['user_id'] }};
            $('#Create').click(function() {
                $('#savedata').val("create");
                $('#createForm').trigger("reset");
                $('#modal-create').modal('show');
            });

            $('#CreatePartner').click(function() {
                $('#savedata_partner').val("create");
                $('#createFormPartner').trigger("reset");
                $('#modal-createpartner').modal('show');
            });

            //savedata
            $('#savedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const code = $("#code").val();
                const dept = $("#dept").val();
                const logo = $("#logo").val();
                //.log("Data being sent: " + $('#createForm').serialize());
                const formData = new FormData($('#createForm')[0]);
                formData.append('logo', $('#logo')[0].files[0]);

                $.ajax({

                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('user.store') }}",
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
                                setTimeout("location.href = '{{ route('user') }}';",
                                    1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedata').html('ลองอีกครั้ง');
                                $('#createForm').trigger("reset");
                                $('.code_err').text(data.error.name);
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'warning',
                                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                                    html: `เนื่องจากกรอกข้อมูลไม่ครบถ้วน`,
                                    showConfirmButton: true,
                                    timer: 2000
                                });
                            }

                        }
                    },

                    statusCode: {
                        400: function(xhr) {
                            var response = xhr.responseJSON;
                            $('#savedata').html('ลองอีกครั้ง');
                            $('.code_err').text(response.message);
                            Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                html: response.message,
                                showConfirmButton: true,
                                timer: 2000
                            });
                        }
                    }


                });
            });


            //ShowEdit
            $('body').on('click', '.edit-item', function() {

                const id = $(this).data('id');
                //console.log(id);
                $('#modal-edit').modal('show');
                $.get('api/user/edit/' + id, function(data) {
                    console.log(data);
                    $('#id_edit').val(data.id);
                    $('#code_edit').val(data.api_data.code);
                    $('#name_edit').val(data.api_data.email);
                    // $('#dept_edit').val(data.dept);
                    $('#dept_edit2').val(data.dept);
                    $('#logo_img').attr('src', data.logo);
                    if (data.role_type == null || data.role_type === "") {
                        $('#role_type_edit option[value=""]').prop('selected', true);
                    } else {
                        $('#role_type_edit option[value="' + data.role_type + '"]').prop('selected',
                            true);
                    }
                    if (data.dept == null || data.dept === "") {
                        $('#dept_edit option[value=""]').prop('selected', true);
                    } else {
                        $('#dept_edit option[value="' + data.dept + '"]').prop('selected', true);
                    }


                    $('input[name="r1"][value="' + data.active + '"]').prop('checked', true);
                    handleRoleChange();


                });


            });

            //update
            $('#updatedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const user_id = $("#name_edit").val();
                const id = $("#id_edit").val();
                const role = $("#role_type_edit").val();
                const r1 = $("#r1").val();
                const dept = $("dept_edit").val();
                const dept2 = $("dept_edit2").val();
                const logo = $("logo_edit").val();

                const formData = new FormData($('#editForm')[0]);
                formData.append('logo_edit', $('#logo_edit')[0].files[0]);
                // for (let [key, value] of formData.entries()) {
                //     console.log(key, value);
                // }
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "api/user/update/" + id,
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
                                setTimeout("location.href = '{{ route('user') }}';",
                                    1500);
                            } else {

                                $('#update').html('ลองอีกครั้ง');

                                //$('#userFormEdit').trigger("reset");
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

            //Delete
            $('body').on('click', '.delete-item', function() {

                const id = $(this).data("id");
                //console.log(id);

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
                            url: 'api/user/destroy/' + id + '/' + user_id,

                            success: function(data) {
                                //tableUser.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout(
                                    "location.href = '{{ route('user') }}';",
                                    1500);

                            },
                            // statusCode: {
                            //     400: function() {
                            //         Swal.fire({
                            //             position: 'top-center',
                            //             icon: 'error',
                            //             title: 'ไม่สามารถลบได้ ต้อง Disable เท่านั้น',
                            //             showConfirmButton: true,
                            //             timer: 2500
                            //         });
                            //     }
                            // }

                        });

                    }
                });

            });

            //savePartner
            $('#savedata_partner').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();

                const formData = new FormData($('#createFormPartner')[0]);
                formData.append('logoPartner', $('#logoPartner')[0].files[0]);

                $.ajax({

                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('partner.store') }}",
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

                                $('#createFormPartner')[0].reset();
                                $('#modal-create').modal('hide');

                                // table.draw();
                                setTimeout("location.href = '{{ route('user') }}';",
                                    1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedata_partner').html('ลองอีกครั้ง');
                                $('#createFormPartner').trigger("reset");
                                $('.username_err').text(data.error.name);
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'warning',
                                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                                    html: `เนื่องจากกรอกข้อมูลไม่ครบถ้วน`,
                                    showConfirmButton: true,
                                    timer: 2000
                                });
                            }

                        }
                    },

                    statusCode: {
                        400: function(xhr) {
                            var response = xhr.responseJSON;
                            $('#savedata_partner').html('ลองอีกครั้ง');
                            $('.username_err').text(response.message);
                            Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                html: response.message,
                                showConfirmButton: true,
                                timer: 2000
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
