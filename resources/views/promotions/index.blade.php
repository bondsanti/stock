@extends('layouts.app')

@section('title', 'โปรโมชั่น')

@section('content')
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;
    @endphp
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">โปรโมชั่นทั้งหมด

                        @if ($isRole->role_type=="SuperAdmin")
                        <button type="button" class="btn bg-gradient-primary" id="Create">
                            <i class="fa fa-plus"></i> เพิ่มโปรโมชั่น
                        </button>
                        @elseif ($isRole->role_type == "User" && $isRole->dept == "Marketing")
                            <button type="button" class="btn bg-gradient-primary" id="Create">
                                <i class="fa fa-plus"></i> เพิ่มโปรโมชั่น
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


                    <div class="card card-info card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                        href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                        aria-selected="true"><i class="nav-icon fas fa-bullhorn"></i> โปรโมชั่น ปัจจุบัน</a>
                                </li>
                                @if ($isRole->role_type != 'Sale')
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-expired-tab" data-toggle="pill"
                                        href="#custom-tabs-one-expired" role="tab"
                                        aria-controls="custom-tabs-one-expired" aria-selected="false"><i
                                            class="nav-icon fas fa-layer-group"></i> โปรโมชั่น หมดอายุ</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">

                                <!-- โปรโมชั่น -->
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">
                                    <div class="row">
                                    @if ($promotions->isEmpty())
                                    <button type="button" class="btn btn-danger btn-block mb-3">
                                        <h4 class="mt-2"> ไม่มีโปรโมชั่น <i class="fa fa-exclamation"></i> </h4>
                                    </button>
                                    @else
                                        @foreach ($promotions as $promotion)
                                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" >

                                                    <div class="card bg-gradient-light d-flex flex-fill">
                                                        <div class="card-header text-muted border-bottom-0">
                                                        @if ($isRole->role_type=="SuperAdmin")

                                                            <button class="btn bg-gradient-info btn-xs edit-item"
                                                            data-id="{{ $promotion->id }}">
                                                            แก้ไข
                                                            </button>

                                                            <button class="btn bg-gradient-danger btn-xs delete-item"
                                                            data-id="{{ $promotion->id }}">
                                                            ลบ
                                                            </button>
                                                        @elseif ($isRole->role_type == "User" && $isRole->dept == "Marketing")
                                                            <button class="btn bg-gradient-info btn-xs edit-item"
                                                            data-id="{{ $promotion->id }}">
                                                            แก้ไข
                                                            </button>

                                                            <button class="btn bg-gradient-danger btn-xs delete-item"
                                                            data-id="{{ $promotion->id }}">
                                                            ลบ
                                                            </button>
                                                        @endif
                                                        </div>
                                                        <div class="card-body pt-0 show-item pointer" data-id="{{ $promotion->id }}">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <h2 class="lead"><b>โครงการ</b>
                                                                        {{ Str::limit($promotion->name, 13) }}</h2>
                                                                    <p class="text-muted text-sm"><b>รายละเอียด: </b>
                                                                        {{ Str::limit($promotion->title, 18) }}</p>
                                                                    <ul class="ml-4 mb-0 fa-ul text-muted">
                                                                        <li class="small"><span class="fa-li"><i
                                                                                    class="fas fa-lg fa-building"></i></span>
                                                                            วันเริ่ม :
                                                                            {{ date('d/m/Y', strtotime($promotion->startdate)) }}</li>
                                                                        <li class="small"><span class="fa-li"><i
                                                                                    class="fas fa-lg fa-phone"></i></span>
                                                                            วันหมดอายุ :
                                                                            {{ date('d/m/Y', strtotime($promotion->expire)) }}</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-5 text-center">
                                                                    @if ($promotion->image)
                                                                        <img src="{{ $promotion->image }}"
                                                                            class="img-fluid"><!--equal-size-image-->
                                                                    @else
                                                                        <img src="{{ url('uploads/noimage.jpg') }}"
                                                                            class="img-fluid">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                            </div>
                                        @endforeach
                                    @endif
                                    </div>
                                    <div class="row">
                                         <!-- Display pagination links -->
                                         <div class="pagination">
                                            <ul class="pagination">
                                                <li class="page-item {{ $promotions->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $promotions->previousPageUrl() }}"
                                                        aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>

                                                <!-- Loop through each pagination link -->
                                                @foreach ($promotions->links() as $link)
                                                    <li class="page-item {{ $link['active'] ? 'active' : '' }}">
                                                        <a class="page-link" href="{{ $link['url'] }}">{{ $loop->iteration }}</a>
                                                    </li>
                                                @endforeach

                                                <li class="page-item {{ $promotions->hasMorePages() ? '' : 'disabled' }}">
                                                    <a class="page-link" href="{{ $promotions->nextPageUrl() }}" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- หมดอายุ -->
                                <div class="tab-pane fade" id="custom-tabs-one-expired" role="tabpanel" aria-labelledby="custom-tabs-one-expired-tab">

                                    <div class="row">


                                            @if ($promotionsExp->isEmpty())
                                            <button type="button" class="btn btn-danger btn-block mb-3">
                                                <h4 class="mt-2"> ไม่มีโปรโมชั่น <i class="fa fa-exclamation"></i> </h4>
                                            </button>
                                            @else
                                                @foreach ($promotionsExp as $promotion)
                                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" >

                                                            <div class="card bg-gradient-light d-flex flex-fill">
                                                                <div class="card-header text-muted border-bottom-0">

                                                                </div>
                                                                <div class="card-body pt-0 show-item pointer" data-id="{{ $promotion->id }}">
                                                                    <div class="row">
                                                                        <div class="col-7">
                                                                            <h2 class="lead"><b>โครงการ</b>
                                                                                {{ Str::limit($promotion->name, 10) }}</h2>
                                                                            <p class="text-muted text-sm"><b>รายละเอียด: </b>
                                                                                {{ Str::limit($promotion->title, 18) }}</p>
                                                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                                                <li class="small"><span class="fa-li"><i
                                                                                            class="fas fa-lg fa-building"></i></span>
                                                                                    วันเริ่ม :
                                                                                    {{ date('d/m/Y', strtotime($promotion->startdate)) }}</li>
                                                                                <li class="small"><span class="fa-li"><i
                                                                                            class="fas fa-lg fa-phone"></i></span>
                                                                                    วันหมดอายุ :
                                                                                    {{ date('d/m/Y', strtotime($promotion->expire)) }}</li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-5 text-center">
                                                                            @if ($promotion->image)
                                                                                <img src="{{ $promotion->image }}"
                                                                                    class="img-fluid"><!--equal-size-image-->
                                                                            @else
                                                                                <img src="{{ url('uploads/noimage.jpg') }}"
                                                                                    class="img-fluid">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                    </div>
                                                @endforeach
                                            @endif



                                    </div>
                                    <div class="row">
                                                                                           <!-- Display pagination links -->
                                                                                           <div class="pagination">
                                                                                            <ul class="pagination">
                                                                                                <li class="page-item {{ $promotionsExp->onFirstPage() ? 'disabled' : '' }}">
                                                                                                    <a class="page-link" href="{{ $promotionsExp->previousPageUrl() }}"
                                                                                                        aria-label="Previous">
                                                                                                        <span aria-hidden="true">&laquo;</span>
                                                                                                    </a>
                                                                                                </li>

                                                                                                <!-- Loop through each pagination link -->
                                                                                                @foreach ($promotionsExp->links() as $link)
                                                                                                    <li class="page-item {{ $link['active'] ? 'active' : '' }}">
                                                                                                        <a class="page-link" href="{{ $link['url'] }}">{{ $loop->iteration }}</a>
                                                                                                    </li>
                                                                                                @endforeach

                                                                                                <li class="page-item {{ $promotionsExp->hasMorePages() ? '' : 'disabled' }}">
                                                                                                    <a class="page-link" href="{{ $promotionsExp->nextPageUrl() }}" aria-label="Next">
                                                                                                        <span aria-hidden="true">&raquo;</span>
                                                                                                    </a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="modal fade" id="modal-create">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">เพิ่มโปรโมชั่น</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="createForm" name="createForm" class="form-horizontal" enctype="multipart/form-data">

                            @csrf
                            <div class="modal-body">

                                <div class="box-body">

                                    <div class="form-group row justify-content-center">

                                            <img src="" id="img-show" class="img-fluid ">

                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">รูปภาพ</label>
                                        <div class="col-sm-6">
                                            <input type="file" class=""
                                                        onchange="previewImage(event)" accept="image/jpeg"
                                                        id="image" name="image">
                                            {{-- <input type="file" id="file" name="image"
                                                onchange="previewImage(event)" accept="image/jpeg"> --}}
                                            {{-- <p class="text-danger mt-1 name_err"></p> --}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">ชื่อโปรโมชั่น</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="title" name="title" autocomplete="off">
                                            <small class="text-danger mt-1 title_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">วันเรื่ม</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control datepicker" id="startdate" name="startdate" autocomplete="off">
                                            <small class="text-danger mt-1 startdate_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันหมดอายุ</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control datepicker" id="expire" name="expire" autocomplete="off">
                                            <small class="text-danger mt-1 expire_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">สำหรับทีม</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="show_channel" name="show_channel">
                                                <option value="0">All</option>
                                                <option value="1">Sale-In</option>
                                                <option value="3">Agent</option>
                                            </select>
                                            <small class="text-danger mt-1 show_channel_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">Youtube</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="youtube_url"
                                                name="youtube_url" autocomplete="off">
                                            <small class="text-danger mt-1 youtube_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">โครงการ</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="project_id" name="project_id">
                                                <option value="">เลือก</option>
                                                @foreach ($projects as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                            <small class="text-danger mt-1 project_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">รายละเอียด</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" rows="4" placeholder="" id="summernote" name="content" autocomplete="off"></textarea>
                                            <small class="text-danger mt-1 content_err"></small>
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
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">แก้ไขโปรโมชั่น </h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editForm" name="editForm" class="form-horizontal" enctype="multipart/form-data">

                            @csrf
                            <input type="hidden" class="form-control" id="id_edit" name="id_edit">
                            <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{$dataLoginUser->id}}">
                            <div class="modal-body">

                                <div class="box-body">

                                    <div class="form-group row justify-content-center">

                                            <img src="" id="img-edit" class="img-fluid">

                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">รูปภาพ</label>
                                        <div class="col-sm-6">


                                            <input type="file" class=""
                                                        onchange="previewImage2(event)" accept="image/jpeg"
                                                        id="image_edit" name="image_edit">

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">ชื่อโปรโมชั่น</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="title_edit" name="title_edit" autocomplete="off">
                                            <small class="text-danger mt-1 title_edit_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 col-form-label text-right">วันเรื่ม</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control datepicker" id="startdate_edit" name="startdate_edit" autocomplete="off">
                                            <small class="text-danger mt-1 startdate_edit_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">วันหมดอายุ</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control datepicker" id="expire_edit" name="expire_edit" autocomplete="off">
                                            <small class="text-danger mt-1 expire_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">สำหรับทีม</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="show_channel_edit" name="show_channel_edit" autocomplete="off">
                                                <option value="0">All</option>
                                                <option value="1">Sale-In</option>
                                                <option value="2">Agent</option>
                                            </select>
                                            <small class="text-danger mt-1 show_channel_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">Youtube</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="youtube_url_edit"
                                                name="youtube_url_edit" autocomplete="off">
                                            <small class="text-danger mt-1 youtube_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">โครงการ</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="project_id" name="project_id">
                                                <option value="">เลือก</option>
                                                @foreach ($projects as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                            <small class="text-danger mt-1 project_err"></small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3"
                                            class="col-sm-4 col-form-label text-right">รายละเอียด</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" rows="3" placeholder=""  id="content_edit" name="content_edit" autocomplete="off"></textarea>
                                            <small class="text-danger mt-1 content_err"></small>
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                        class="fa fa-times"></i> ยกเลิก</button>

                                <button type="button" class="btn btn-success" id="updatedata" value="update"><i
                                        class="fa fa-save"></i> บันทึก</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-detail">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">โปรโมชั่น <span id=""></span></h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="box-body">

                                <div class="card-body">
                                    <div class="form-group row justify-content-center">
                                        <img id="img-detail" class="size-image"alt="">
                                    </div>
                                    <hr>
                                    <dl class="row">

                                        <dt class="col-sm-3 text-right">ชื่อโปรโมชั่น : </dt>
                                        <dd class="col-sm-9 text-info text-left" id="title_show"></dd>
                                        <dt class="col-sm-3 text-right">สำหรับทีม : </dt>
                                        <dd class="col-sm-9 text-info text-left" id="show_channel_show"></dd>
                                        <dt class="col-sm-3 text-right">วันที่เริ่ม : </dt>
                                        <dd class="col-sm-9 text-info text-left" id="dstart_show"></dd>
                                        <dt class="col-sm-3 text-right">วันหมดอายุ : </dt>
                                        <dd class="col-sm-9 text-info text-left" id="dend_show"></dd>
                                        <dt class="col-sm-3 text-right">รายละเอียด : </dt>
                                        <dd class="col-sm-9 text-info text-left" id="content_show"></dd>
                                        <dt class="col-sm-3 text-right">Youtube : </dt>
                                        <dd class="col-sm-9 text-info text-left" >
                                            <iframe width="420" height="315" src=""
                                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen id="youtube_show">
                                    </iframe>
                                      </dd>
                                    </dl>
                                </div>

                        </div>

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
    $(function () {
      // Summernote
      $('#summernote').summernote()

    })
  </script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // รูปแบบวันที่
                autoclose: true,
            });

            $('#startdate').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#expire').datepicker('setStartDate', selectedStartDate);
            });

            const user_id = {{ $dataLoginUser->id }};
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
                const image = $("#image").val();
                const title = $("#title").val();
                const content = $("#content").val();
                const startdate = $("#startdate").val();
                const expire = $("#expire").val();
                const show_channel = $("#show_channel").val();
                const youtube_url = $("#youtube_url").val();
                const project_id = $("#project_id").val();

                const formData = new FormData($('#createForm')[0]);
                formData.append('image', $('#image')[0].files[0]);
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('promotion.store') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                                setTimeout("location.href = '{{ route('promotion') }}';",
                                    1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedata').html('ลองอีกครั้ง');
                                $('#createForm').trigger("reset");
                                $('.title_err').text(data.error.title);
                                $('.startdate').text(data.error.startdate);
                                $('.expire').text(data.error.expire);
                                $('.project').text(data.error.project_id);
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

                });
            });

          //ShowEdit
            $('body').on('click', '.edit-item', function() {

                const id = $(this).data('id');

                $('#modal-edit').modal('show');

                $.get('/api/promotion/edit/' + id, function(data) {
                    //console.log(data);
                    $('#id_edit').val(data.id);
                    $('#img-edit').attr('src', data.image);
                    $('#title_edit').val(data.title);
                    $('#startdate_edit').val(data.startdate);
                    $('#expire_edit').val(data.expire);
                    $('#youtube_url_edit').val(data.youtube_url);
                    $('#content_edit').summernote({
                    });
                    $('#content_edit').summernote('code', data.content);
                    $('#show_channel_edit option[value="' + data.show_channel + '"]').prop('selected', true);
                    $('#project_id option[value="' + data.project_id + '"]').prop('selected', true);
                });
            });

            //ShowDetail
            $('body').on('click', '.show-item', function() {

                const id = $(this).data('id');

                $('#modal-detail').modal('show');

                $.get('/api/promotion/edit/' + id, function(data) {
                    console.log(data);
                    $('#img-detail').attr('src', data.image);
                    $('#title_show').text(data.title);
                    $('#dstart_show').text(data.startdate);
                    $('#dend_show').text(data.expire);
                    $('#content_show').html(data.content);
                    if (data.youtube_url) {
                        $('#youtube_show').attr('src', data.youtube_url);
                    } else {
                        // Display a link or handle the empty URL as needed
                        $('#youtube_show').replaceWith('<a href="#">No YouTube link available</a>');
                    }

                    let showChannelText = '';
                    switch (data.show_channel) {
                        case 0:
                            showChannelText = 'ALL';
                            break;
                        case 1:
                            showChannelText = 'Sale-in';
                            break;
                        case 2:
                            showChannelText = 'Agent';
                            break;
                        default:
                            // Handle any other values if necessary
                            showChannelText = 'Unknown'; // Default text for unknown values
                            break;
                    }
                    $('#show_channel_show').text(showChannelText);


                });
            });

             //Del Contract
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
                            url: '/api/promotion/destroy/' + id + '/' + user_id,
                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบข้อมูลสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout("location.href = '{{ route('promotion') }}';",
                                1500);

                            },
                            // statusCode: {
                            //     400: function() {
                            //         Swal.fire({
                            //             position: 'top-center',
                            //             icon: 'error',
                            //             title: 'ไม่สามารถลบได้ เนื่องจาก ข้อมูลนี้มีใช้อยู่ในฐานข้อมูล',
                            //             showConfirmButton: true,
                            //             timer: 2500
                            //         });
                            //     }
                            // }
                        });
                    }
                });

            });

            //updateData
            $('#updatedata').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const id = $("#id_edit").val();
                const image = $("#image_edit").val();
                const title = $("#title_edit").val();
                const content = $("#content_edit").val();
                const startdate = $("#startdate_edit").val();
                const expire = $("#expire_edit").val();
                const show_channel = $("#show_channel_edit").val();
                const youtube_url = $("#youtube_url_edit").val();
                const project_id = $("#project_id_edit").val();

                const formData = new FormData($('#editForm')[0]);
                formData.append('image_edit', $('#image_edit')[0].files[0]);
                //console.log(id);
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: '/api/promotion/update/' + id,
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
                                setTimeout("location.href = '{{ route('promotion') }}';",
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
                    $('.' + key + '_err').text(value);
                });
            }



        });
    </script>
    <script>
        function previewImage(event) {
            var imageElement = document.getElementById('img-show');
            var fileInput = event.target;

            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imageElement.src = e.target.result;
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
        function previewImage2(event) {
            var imageElement = document.getElementById('img-edit');
            var fileInput = event.target;

            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imageElement.src = e.target.result;
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>

@endpush
