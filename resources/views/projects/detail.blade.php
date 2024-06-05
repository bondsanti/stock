@extends('layouts.app')

@section('title', 'โครงการ')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">รายละเอียด : <font class="text-info"> โครงการ {{ $project->name }} </font>

                    </h1>

                </div><!-- /.col -->
                <div class="col-sm-6 ">
                    @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin')
                        <small class="float-sm-right"><a class="btn bg-gradient-warning btn-sm"
                                href="{{ url('/projects/edit/' . $project->id) }}"> แก้ไข</a></small>
                    @endif
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
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">ข้อมูลโครงการ</h3>

                        </div>
                        <div class="card-body">
                            <div class="form-group row justify-content-center">

                                @if ($project->logo)
                                    <img src="{{ $project->logo }}" class="size-image" alt="">
                                @else
                                    <img src="{{ url('uploads/noimage.jpg') }}" class="size-image" alt="">
                                @endif

                            </div>


                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="name">ชื่อ</label>
                                    <div class="form-inline">
                                        <label for="name" class="font-weight-light text-info"> {{ $project->name }}
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="address">ที่อยู่</label>
                                    <div class="form-inline">
                                        <label for="address" class="font-weight-light text-info">
                                            {{ $project->address ? $project->address : '-' }} </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="type_project">ประเภท</label>
                                    <div class="form-inline">
                                        <label for="type_project" class="font-weight-light text-info">
                                            {{ $project->type_project ? $project->type_project : '-' }} </label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="building">จำนวน อาคาร</label>
                                    <div class="form-inline">
                                        <label for="building" class="font-weight-light text-info">
                                            {{ $project->building ? $project->building : '-' }} </label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="unit">จำนวน ยูนิต</label>
                                    <div class="form-inline">
                                        <label for="unit" class="font-weight-light text-info">
                                            {{ $project->unit ? $project->unit : '-' }} </label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="area">พื้นที่เริ่มต้น</label>
                                    <div class="form-inline">
                                        <label for="area"
                                            class="font-weight-light text-info">{{ $project->area ? $project->area : '0' }}
                                            ตร.ม.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="price">ราคาเริ่มต้น</label>
                                    <div class="form-inline">
                                        <label for="price"
                                            class="font-weight-light text-info">{{ $project->start_price ? number_format($project->start_price) : '0' }}
                                            บาท</label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="price">ใบราคา/โปรโมชั่น</label>
                                    <div class="form-inline">
                                        @if (isset($files))
                                        <a href="{{ $files->file }}" target="_blank">
                                            <h4>
                                                <span id="badge" class="badge rounded-pill bg-warning">
                                                    <i class="far fa-file-pdf"></i> ไฟล์หมดอายุ :
                                                    {{ date('d/m/Y', strtotime($files->exp)) }}
                                                </span>
                                            </h4>
                                        </a>
                                    @else
                                    <label for="price"
                                    class="font-weight-light text-info">  - </label>

                                    @endif


                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="description">รายละเอียด</label>
                                    <div class="form-inline">
                                        <label for="description" class="font-weight-light text-info">
                                            {{ $project->description ? $project->description : '-' }} </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="unit">Link</label>
                                    <div class="form-inline">
                                        <label for="unit" class="font-weight-light text-info">
                                            <a href="{{ $project->urllink }}"
                                                target="_blank">{{ $project->urllink ? $project->urllink : '-' }}</a>

                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">รูปแบบห้องชุด</h3>
                        </div>

                        <div class="card-body">
                            <div id="accordion">
                                @foreach ($plans as $key => $plan)
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100" data-toggle="collapse"
                                                    href="#collapse{{ $plan->id }}">
                                                    {{ $plan->name }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{ $plan->id }}"
                                            class="collapse {{ $key == 0 ? 'show' : '' }}" data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="form-group row justify-content-center">
                                                    @if ($plan->plan_image)
                                                        <img src="{{ $plan->plan_image }}" class="size-image"
                                                            alt="">
                                                    @else
                                                        <img src="{{ url('uploads/noimage.jpg') }}" class="size-image"
                                                            alt="">
                                                    @endif
                                                </div>
                                                <dl class="row">

                                                    <dt class="col-sm-6 text-right">ชื่อ : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $plan->name }}</dd>

                                                    <br>
                                                    <dt class="col-sm-6 text-right">พื้นที่ : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $plan->area }}</dd>
                                                    <br>
                                                    <dt class="col-sm-6 text-right">จำนวน ห้องนอน : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $plan->bed_room }}</dd>
                                                    <br>
                                                    <dt class="col-sm-6 text-right">จำนวน ห้องน้ำ : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $plan->bath_room }}</dd>
                                                    <br>
                                                    <dt class="col-sm-6 text-right">จำนวน ห้องนั่งเล่น : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $plan->living_room }}</dd>
                                                    <br>
                                                    <dt class="col-sm-6 text-right">จำนวน ห้องครัว : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $plan->kitchen_room }}</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">รูปแบบ Floor Plan</h3>
                        </div>
                        <div class="card-body">
                            <div id="accordionfloor">
                                @foreach ($floors as $key => $floor)
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100" data-toggle="collapse"
                                                    href="#collapse{{ $floor->id }}">
                                                    {{ $floor->name }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{ $floor->id }}"
                                            class="collapse {{ $key == 0 ? 'show' : '' }}"
                                            data-parent="#accordionfloor">
                                            <div class="card-body">
                                                <div class="form-group row justify-content-center">
                                                    @if ($floor->image)
                                                        <img src="{{ $floor->image }}" class="size-image"
                                                            alt="">
                                                    @else
                                                        <img src="{{ url('uploads/noimage.jpg') }}" class="size-image"
                                                            alt="">
                                                    @endif
                                                </div>
                                                <dl class="row">

                                                    <dt class="col-sm-6 text-right">ชื่อ : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $floor->name }}</dd>

                                                    <br>
                                                    <dt class="col-sm-6 text-right">ชั้นที่ : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $floor->floor }}</dd>
                                                    <br>
                                                    <dt class="col-sm-6 text-right">ตึก : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $floor->building }}</dd>
                                                    <br>
                                                    <dt class="col-sm-6 text-right">จำนวนห้องทั้งหมด : </dt>
                                                    <dd class="col-sm-6 text-info">{{ $floor->rooms }}</dd>

                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
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
                                title: 'ชื่อโครงการซ้ำ',
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
