@extends('layouts.app')

@section('title', 'โครงการ')

@section('content')
@push('style')
<style>
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


                    <h1 class="m-0">โครงการ

                        @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin')

                        <button type="button" class="btn btn-primary" id="Create">
                            <i class="fa fa-plus"></i> เพิ่มข้อมูล
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

            @if ($isRole->role_type =="SuperAdmin" || $isRole->role_type =="Admin" || $isRole->role_type =="Staff")


            <div class="row">
                <div class="col-md-12">


                    <div class="row">
                        <div class="col-md-3 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-lightblue">
                                    <div class="inner">
                                        <h3>{{ number_format($all) }}</h3>

                                        <p>All</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-building"></i>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($active) }}</h3>

                                        <p>Active</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-3 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-danger">
                                    <div class="inner">
                                        <h3>{{ number_format($unactive) }}</h3>

                                        <p>UnActive</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-ban"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-3 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-secondary">
                                    <div class="inner">
                                        <h3>{{ number_format($hidden) }}</h3>

                                        <p>Hidden</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-eye-slash"></i>
                                    </div>

                                </div>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

            @endif

            <!--Table-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">โครงการ ทั้งหมด</h3>

                        </div>

                        <div class="card-body table-responsive">

                            <table id="table" class="table table-hover table-striped text-nowrap table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>โครงการ</th>

                                        <th class="text-center">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project as $item)
                                        <tr>
                                            <td width="5%">{{ $loop->index + 1 }}</td>
                                            <td class="text-sm" width="80%">
                                                โครงการ
                                                <b class="text-navy">
                                                    {{ $item->name }}
                                                </b>
                                                <br />
                                                <small>
                                                    @if ($item->active == 1)
                                                    <span id="badge" class="badge bg-success">Active</span>
                                                    @elseif($item->active == 2)
                                                    <span id="badge" class="badge bg-secondary">Hidden</span>
                                                    @else
                                                    <span id="badge" class="badge bg-danger">UnActive</span>
                                                    @endif

                                                </small>
                                            </td>


                                            <td width="15%" class="text-center">
                                                <a href="{{ url('/projects/detail/' . $item->id) }}"
                                                    class="btn bg-gradient-info btn-sm edit-item" data-toggle="tooltip" data-placement="top" title="ดูรายละเอียด">
                                                    <i class="fa fa-th-list">
                                                    </i>

                                                </a>
                                                @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin')

                                                <a href="{{ url('/projects/edit/' . $item->id) }}"
                                                    class="btn bg-gradient-info btn-sm edit-item" data-toggle="tooltip" data-placement="top" title="แก้ไข">
                                                    <i class="fa fa-pencil-square">
                                                    </i>

                                                </a>
                                                <button class="btn bg-gradient-danger  btn-sm delete-item" data-toggle="tooltip" data-placement="top" title="ลบ"
                                                    data-id="{{ $item->id }}">
                                                    <i class="fa fa-trash">
                                                    </i>
                                                </button>
                                                @elseif ($isRole->role_type == "User" && $isRole->dept == "Legal")
                                                <a href="{{ url('/projects/edit/' . $item->id) }}"
                                                    class="btn bg-gradient-info btn-sm edit-item" data-toggle="tooltip" data-placement="top" title="แก้ไข">
                                                    <i class="fa fa-pencil-square">
                                                    </i>

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
        </div><!-- /.container-fluid -->

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
                        {{-- <form id="createForm" name="createForm"  method="post" action="{{route('project.insert')}}" class="form-horizontal"> --}}
                        @csrf
                        <div class="modal-body">

                            <div class="box-body">

                                <div class="form-group row">
                                    <label for="inputEmail3"
                                        class="col-sm-4 col-form-label text-right">ชื่อโครงการ</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="" autocomplete="off">
                                        <p class="text-danger mt-1 name_err"></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 col-form-label text-right">สถานะ</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="active" name="active">
                                            <option value="1">Active</option>
                                            <option value="0">UnActive</option>
                                            <option value="2">Hidden ซ่อน</option>
                                        </select>
                                        {{-- <small class="text-danger mt-1 role_err"></small> --}}
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
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
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

            });

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
                const active = $("#active").val();
                //.log("Data being sent: " + $('#createForm').serialize());

                $.ajax({

                    data: $('#createForm').serialize(),
                    url: "{{ route('project.store') }}",
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
                                setTimeout("location.href = '{{ route('project') }}';",
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
                                    timer: 1500
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
                                title: 'ชื่อโครงการนี้มีอยู่แล้ว',
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
                            type: "POST",
                            url: 'api/project/predelete/' + id + '/' + user_id,

                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout(
                                    "location.href = '{{ route('project') }}';",
                                    1500);

                            },
                            statusCode: {
                                400: function() {

                                }
                            }
                        });
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
