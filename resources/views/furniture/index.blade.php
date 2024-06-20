@extends('layouts.app')

@section('title', 'เฟอร์นิเจอร์')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">เฟอร์นิเจอร์ทั้งหมด <button type="button" class="btn bg-gradient-primary" id="Create">
                            <i class="fa fa-plus"></i> เพิ่มข้อมูล
                        </button></h1>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">


            <!--Table-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">เฟอร์นิเจอร์ ทั้งหมด</h3>

                        </div>

                        <div class="card-body table-responsive">

                            <table id="table" class="table table-hover table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>เฟอร์นิเจอร์</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($furnitures as $item)
                                        <tr>
                                            <td width="5%">{{ $loop->index + 1 }}</td>
                                            <td class="text-sm" width="80%">

                                                <b class="text-navy">
                                                    {{ $item->name }}
                                                </b>
                                                {{-- <br />
                                                <small>
                                                    รายละเอียด : {{ $item->detail }}
                                                </small> --}}
                                            </td>


                                            <td width="15%" class="text-center">

                                                <button class="btn bg-gradient-info btn-sm edit-item"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fa fa-pencil-square">
                                                    </i>

                                                </button>
                                                <button class="btn bg-gradient-danger  btn-sm delete-item"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fa fa-trash">
                                                    </i>
                                                </button>
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
                        <div class="modal-header">
                            <h5 class="modal-title">เพิ่มข้อมูล</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="createForm" name="createForm" class="form-horizontal">
                            {{-- <form id="createForm" name="createForm"  method="post" action="{{route('project.insert')}}" class="form-horizontal"> --}}
                            @csrf
                            <div class="modal-body">

                                <div class="box-body">

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">ชื่อเฟอรนิเจอร์</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="" autocomplete="off">
                                            <p class="text-danger mt-1 name_err"></p>
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
                            <input type="hidden" class="form-control" id="user_id" name="user_id" placeholder=""
                                autocomplete="off" value="{{ $dataLoginUser['user_id'] }}">
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
                        <div class="modal-header">
                            <h5 class="modal-title">แก้ไข ข้อมูล</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editForm" name="editForm" class="form-horizontal">

                            @csrf
                            <div class="modal-body">

                                <div class="box-body">

                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">ชื่อเฟอรนิเจอร์</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="name_edit" name="name_edit"
                                                placeholder="" autocomplete="off">
                                            <p class="text-danger mt-1 name_edit_err"></p>
                                        </div>
                                    </div>




                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn bg-gradient-danger" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>
                                <button type="button" class="btn bg-gradient-success"id="updatedata" value="update"><i
                                        class="fa fa-save"></i> อัพเดท</button>
                            </div>
                            <input type="hidden" class="form-control" id="user_id" name="user_id" placeholder=""
                                autocomplete="off" value="{{ $dataLoginUser['user_id'] }}">

                                <input type="hidden" class="form-control" id="id_edit" name="id_edit" placeholder=""
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
        $(document).ready(function() {


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

            const user_id = {{ $dataLoginUser['user_id'] }};

            //Create modal
            $('#Create').click(function() {
                $('#savedata').val("create");
                $('#createForm').trigger("reset");
                $('#modal-create').modal('show');
            });

            //Save
            $('#savedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const name = $("#name").val();
                //.log("Data being sent: " + $('#createForm').serialize());

                $.ajax({

                    data: $('#createForm').serialize(),
                    url: "{{ route('furniture.store') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // รับ CSRF token จาก meta tag ในหน้า HTML
                    },
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
                                setTimeout("location.href = '{{ route('furniture') }}';",
                                    1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedata').html('ลองอีกครั้ง');
                                $('#createForm').trigger("reset");
                                $('.name_err').text(data.error.name);
                                Swal.fire({
                                    position: 'top-center',
                                    icon: 'error',
                                    title: 'ไม่สามารถบันทึกข้อมูลได้',
                                    html: `เนื่องจากกรอกข้อมูลไม่ครบถ้วน`,
                                    showConfirmButton: true,
                                    timer: 2000
                                });
                            }

                        }
                    },

                    statusCode: {
                        400: function() {
                            $('#savedata').html('ลองอีกครั้ง');
                            $('.name_err').text("ซ้ำ");
                            Swal.fire({
                                position: 'top-center',
                                icon: 'error',
                                title: 'ชื่อเฟอรนิเจอร์ซ้ำ',
                                showConfirmButton: true,
                                timer: 1500
                            });
                        }
                    }

                });
            });

            //Del
            $('body').on('click', '.delete-item', function() {
                const id = $(this).data("id");
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
                            url: 'api/furniture/destroy/' + id + '/' + user_id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // รับ CSRF token จาก meta tag ในหน้า HTML
                            },
                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบข้อมูลสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout(
                                    "location.href = '{{ route('furniture') }}';",
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

            //ShowEdit
            $('body').on('click', '.edit-item', function() {

                const id = $(this).data('id');
                $('#modal-edit').modal('show');

                $.get('api/furniture/edit/' + id, function(data) {
                    $('#id_edit').val(data.id);
                    $('#name_edit').val(data.name);
                });
            });

            //updateData
            $('#updatedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const id = $("#id_edit").val();
                const name = $("#name_edit").val();
                const user_id = $("#user_id").val();
                //console.log(id);
                $.ajax({
                    data: $('#editForm').serialize(),
                    url: 'api/furniture/update/' + id,
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
                                setTimeout("location.href = '{{ route('furniture') }}';",
                                    1500);
                            } else {
                                printErrorMsg2(data.error);
                                $('.name_edit_err').text(data.error.name_edit);
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

        });
    </script>
@endpush
