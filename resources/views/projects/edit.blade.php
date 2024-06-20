@extends('layouts.app')

@section('title', 'โครงการ')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">

                        <font class="text-info"> โครงการ {{ $project->name }} </font><sup class="text-red">[project id :
                            {{ $project->id }}]</sup>
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
                                @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin')
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                            href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                                            aria-selected="true"><i class="nav-icon fas fa-hotel"></i> ข้อมูลโครงการ</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                            href="#custom-tabs-one-profile" role="tab"
                                            aria-controls="custom-tabs-one-profile" aria-selected="false"><i
                                                class="nav-icon fas fa-layer-group"></i> Floor</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill"
                                            href="#custom-tabs-one-messages" role="tab"
                                            aria-controls="custom-tabs-one-messages" aria-selected="false"><i
                                                class="nav-icon fas fa-grip"></i> ห้องชุด</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-price-tab" data-toggle="pill"
                                            href="#custom-tabs-one-price" role="tab"
                                            aria-controls="custom-tabs-one-price" aria-selected="false"><i
                                                class="nav-icon fas fa-usd"></i> รายละเอียดใบราคา</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill"
                                            href="#custom-tabs-one-settings" role="tab"
                                            aria-controls="custom-tabs-one-settings" aria-selected="false"><i
                                                class="nav-icon fas fa-file-contract"></i> สัญญา</a>
                                    </li>
                                    {{-- @elseif ($isRole->role_type == 'User' && $isRole->dept == 'Legal')
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-settings-tab" data-toggle="pill"
                                            href="#custom-tabs-one-settings" role="tab"
                                            aria-controls="custom-tabs-one-settings" aria-selected="false"><i
                                                class="nav-icon fas fa-file-contract"></i> สัญญา</a>
                                    </li> --}}
                                @endif
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">

                                <!-- โครงการ -->
                                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-home-tab">
                                    <div class="d-flex justify-content-center">
                                        <h5 class="m-0">ข้อมูลโครงการ {{ $project->name }}</h5>
                                    </div>
                                    <form action="{{ route('project.update.info') }}" method="post"
                                        enctype="multipart/form-data" class="form-horizontal">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        <input type="hidden" name="user_id" value="{{ $dataLoginUser['user_id'] }}">
                                        <div class="row">
                                            <div class="card-body">
                                                <div class="form-group row justify-content-center">
                                                    <div class="col-md-4"></div>
                                                    <div class="col-md-4">
                                                        @if ($project->logo)
                                                            <img src="{{ $project->logo }}" id="img-show"
                                                                class="size-image" alt="">
                                                        @else
                                                            <img src="{{ url('uploads/noimage.jpg') }}" class="size-image"
                                                                alt="" id="img-show">
                                                        @endif

                                                        <div class="form-group">
                                                            <br>
                                                            <label for="exampleInputFile">รูปภาพโครงการ </label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input"
                                                                        onchange="previewImage(event)" accept="image/jpeg"
                                                                        id="logo" name="logo">
                                                                    <label class="custom-file-label"
                                                                        for="exampleInputFile"></label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>สถานะ</label>
                                                            <select class="form-control" id="active" name="active">
                                                                <option value="0"
                                                                    {{ $project->active == '0' ? 'selected' : '' }}>
                                                                    UnActive</option>
                                                                <option value="1"
                                                                    {{ $project->active == '1' ? 'selected' : '' }}>
                                                                    Active</option>
                                                                <option value="2"
                                                                    {{ $project->active == '2' ? 'selected' : '' }}>
                                                                    Hidden ซ่อน</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="name">ชื่อโครงการ</label>
                                                            <input type="text" class="form-control" id="name"
                                                                name="name" placeholder="Name" autocomplete="off"
                                                                value="{{ $project->name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="phone">เบอร์โทรศัพท์</label>
                                                            <input type="text" class="form-control" name="phone"
                                                                placeholder="เบอร์โทรศัพท์" autocomplete="off"
                                                                value="{{ $project->phone }}" maxlength="10">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="start_price">ราคาเริ่มต้น (บาท)</label>
                                                            <input type="text" class="form-control" name="start_price"
                                                                placeholder="ราคาเริ่มต้น" autocomplete="off"
                                                                value="{{ $project->start_price }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="area">พื้นที่เริ่มต้น (ตร.ม.)</label>
                                                            <input type="area" class="form-control" name="area"
                                                                placeholder="พื้นที่เริ่มต้น (ตร.ม.)" autocomplete="off"
                                                                value="{{ $project->area }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>ประเภท</label>
                                                            <select class="form-control select2bs4" name="type_project">
                                                                <option value="คอนโด"
                                                                    {{ $project->type_project == 'คอนโด' ? 'selected' : '' }}>
                                                                    คอนโด</option>
                                                                <option value="ทาวเฮ้าส์"
                                                                    {{ $project->type_project == 'ทาวเฮ้าส์' ? 'selected' : '' }}>
                                                                    ทาวเฮ้าส์</option>
                                                                <option value="บ้านเดี่ยว"
                                                                    {{ $project->type_project == 'บ้านเดี่ยว' ? 'selected' : '' }}>
                                                                    บ้านเดี่ยว</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="building">จำนวน อาคาร</label>
                                                            <input type="number"class="form-control" name="building"
                                                                id="building" placeholder="จำนวน อาคาร"
                                                                autocomplete="off" value="{{ $project->building }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="unit">จำนวน ยูนิต</label>
                                                            <input type="number"class="form-control" name="unit"
                                                                id="unit" placeholder="จำนวน ยูนิต"
                                                                autocomplete="off" value="{{ $project->unit }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="urllink">Link</label>
                                                            <input type="text"class="form-control" name="urllink"
                                                                id="urllink" placeholder="Link" autocomplete="off"
                                                                value="{{ $project->urllink }}">
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    value="1" name="low_rise" id="low_rise"
                                                                    {{ $project->low_rise == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="exampleCheck1">แนวราบ</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input"
                                                                    value="1" name="high_rise" id="high_rise"
                                                                    {{ $project->high_rise == 1 ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="exampleCheck1">แนวสูง</label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="map">Map <sup>ให้กรอก ( 13.5133116 ,
                                                                    100.5195396 ) </sup></label>
                                                            <input type="text"class="form-control" name="map"
                                                                id="map" placeholder="13.5133116,100.5195396"
                                                                autocomplete="off"
                                                                value="{{ $project->map ? $project->map : '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="address">ที่อยู่</label>
                                                            <textarea name="address" class="form-control" rows="3" placeholder="Enter ..." autocomplete="off"> {{ $project->address }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">รายละเอียด</label>
                                                            <textarea name="description" class="form-control" rows="3" placeholder="Enter ..." autocomplete="off"> {{ $project->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-4"></div>
                                                    <div class="col-md-4">
                                                        <td>
                                                            <div class="col-sm-4 offset-4">

                                                                <button type="submit" class="btn btn-success"><i
                                                                        class="fa fa-save"></i> อัพเดท</button>
                                                            </div>

                                                        </td>
                                                    </div>
                                                    <div class="col-md-4"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- floor -->
                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
                                    <h5 class="mb-3">Floor {{ $project->name }}
                                        <div class="float-right">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-xl">
                                                <i class="fa fa-plus"></i> เพิ่ม Floor
                                            </button>
                                        </div>
                                    </h5>
                                    <div class="form-group row">

                                        <div class="col-md-12">
                                            <div id="accordion">
                                                <div class="form-group row">
                                                    @foreach ($floors as $key => $floor)
                                                        <div class="col-md-4">
                                                            <form action="{{ route('project.update.floor') }}"
                                                                method="post" enctype="multipart/form-data"
                                                                id="formFloor{{ $floor->id }}">
                                                                @csrf
                                                                <input type="hidden" value="{{ $floor->id }}"
                                                                    name="floor_id">
                                                                <input type="hidden" name="user_id" id="user_id"
                                                                    value="{{ $dataLoginUser['user_id'] }}">
                                                                <div class="card card-info">
                                                                    <div class="card-header">
                                                                        <h4 class="card-title w-100">
                                                                            <a class="d-block w-100"
                                                                                data-toggle="collapse"
                                                                                href="#collapse{{ $floor->id }}">

                                                                                <i class="nav-icon fas fa-layer-group"></i>
                                                                                {{ $floor->name }}
                                                                                <sup class="text-">[floor id :
                                                                                    {{ $floor->id }}]</sup>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="collapse{{ $floor->id }}"
                                                                        class="collapse {{ $key == 0 ? 'show' : '' }}"
                                                                        data-parent="#accordion">
                                                                        <div class="card-body">
                                                                            <div
                                                                                class="form-group row justify-content-center">
                                                                                @if ($floor->image)
                                                                                    <img src="{{ $floor->image }}"
                                                                                        id="img-show-floor-{{ $floor->id }}"
                                                                                        class="size-image" alt="">
                                                                                @else
                                                                                    <img src="{{ url('uploads/noimage.jpg') }}"
                                                                                        class="size-image" alt=""
                                                                                        id="img-show-floor-{{ $floor->id }}">
                                                                                @endif
                                                                                <div class="form-group">
                                                                                    <br>
                                                                                    <label for="exampleInputFile">รูปภาพ
                                                                                        Floor
                                                                                    </label>
                                                                                    <div class="input-group">
                                                                                        <div class="custom-file">
                                                                                            <input type="file"
                                                                                                class="custom-file-input"
                                                                                                onchange="previewImageFloor{{ $floor->id }}(event)"
                                                                                                accept="image/jpeg"
                                                                                                id="images"
                                                                                                name="iamges">
                                                                                            <label
                                                                                                class="custom-file-label"
                                                                                                for="exampleInputFile"></label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-10">
                                                                                    <label for="name">ชื่อ</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="name" id="name"
                                                                                        placeholder="ชื่อ"
                                                                                        autocomplete="off"
                                                                                        value="{{ $floor->name }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="floor">ชั้น</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="text"
                                                                                            class="form-control col-md-10"
                                                                                            name="floor" id="floor"
                                                                                            placeholder="ชั้น"
                                                                                            autocomplete="off"
                                                                                            value="{{ $floor->floor }}">

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="bed_room">ตึก
                                                                                    </label>
                                                                                    <div class="form-inline">

                                                                                        <input type="text"
                                                                                            class="form-control col-md-10"
                                                                                            name="building" id="building"
                                                                                            placeholder="ตึก"
                                                                                            autocomplete="off"
                                                                                            value="{{ $floor->building }}">

                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="rooms">จำนวน
                                                                                        ห้อง</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="number"
                                                                                            class="form-control col-md-10"
                                                                                            name="rooms" id="rooms"
                                                                                            placeholder="จำนวนห้อง"
                                                                                            autocomplete="off"
                                                                                            value="{{ $floor->rooms }}">
                                                                                        <label for="rooms"
                                                                                            class="col-md-2">ห้อง</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-sm-12 text-center">

                                                                                <button type="submit"
                                                                                    class="btn btn-success"><i
                                                                                        class="fa fa-save"></i>
                                                                                    อัพเดท</button>

                                                                                <button type="button" data-toggle="modal"
                                                                                    data-target="#deleteConfirmationModalF{{ $floor->id }}"
                                                                                    class="btn btn-danger delete-plan">
                                                                                    <i class="fa fa-trash">
                                                                                    </i>
                                                                                    ลบ </button>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>


                                                        </div>

                                                        <div class="modal fade"
                                                            id="deleteConfirmationModalF{{ $floor->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="deleteConfirmationModalF" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form action="{{ route('project.destroy.floor') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $floor->id }}"
                                                                        name="floor_id">
                                                                    <input type="hidden"
                                                                        value="{{ $dataLoginUser['user_id'] }}" name="user_id">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="deleteConfirmationModalLabelF">
                                                                                ยืนยันการลบ</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            คุณแน่ใจหรือไม่ที่ต้องการลบข้อมูลนี้?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">ยกเลิก</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">ยืนยันการลบ</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach


                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- ห้องชุด -->
                                <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-messages-tab">
                                    <h5 class="mb-3">รูปแบบห้องชุด {{ $project->name }}
                                        <div class="float-right">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-xl1">
                                                <i class="fa fa-plus"></i> เพิ่มห้องชุด
                                            </button>
                                        </div>
                                    </h5>

                                    <div class="form-group row">

                                        <div class="col-md-12">
                                            <div id="accordion">
                                                <div class="form-group row">
                                                    @foreach ($plans as $key => $plan)
                                                        <div class="col-md-4">
                                                            <form action="{{ route('project.update.plan') }}"
                                                                method="post" enctype="multipart/form-data"
                                                                id="formPlan{{ $plan->id }}">
                                                                @csrf
                                                                <input type="hidden" value="{{ $plan->id }}"
                                                                    name="plan_id">
                                                                <input type="hidden" name="user_id" id="user_id"
                                                                    value="{{ $dataLoginUser['user_id'] }}">
                                                                <div class="card card-info">
                                                                    <div class="card-header">
                                                                        <h4 class="card-title w-100">
                                                                            <a class="d-block w-100"
                                                                                data-toggle="collapse"
                                                                                href="#collapse{{ $plan->id }}">

                                                                                <i class="nav-icon fas fa-grip"></i>
                                                                                {{ $plan->name }}
                                                                                <sup class="text-">[plan id :
                                                                                    {{ $plan->id }}]</sup>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div id="collapse{{ $plan->id }}"
                                                                        class="collapse {{ $key == 0 ? 'show' : '' }}"
                                                                        data-parent="#accordion">
                                                                        <div class="card-body">
                                                                            <div
                                                                                class="form-group row justify-content-center">
                                                                                @if ($plan->plan_image)
                                                                                    <img src="{{ $plan->plan_image }}"
                                                                                        id="img-show-{{ $plan->id }}"
                                                                                        class="size-image" alt="">
                                                                                @else
                                                                                    <img src="{{ url('uploads/noimage.jpg') }}"
                                                                                        class="size-image" alt=""
                                                                                        id="img-show-{{ $plan->id }}">
                                                                                @endif
                                                                                <div class="form-group">
                                                                                    <br>
                                                                                    <label
                                                                                        for="exampleInputFile">รูปภาพห้องชุด
                                                                                    </label>
                                                                                    <div class="input-group">
                                                                                        <div class="custom-file">
                                                                                            <input type="file"
                                                                                                class="custom-file-input"
                                                                                                onchange="previewImagePlan{{ $plan->id }}(event)"
                                                                                                accept="image/jpeg"
                                                                                                id="plan_image"
                                                                                                name="plan_image">
                                                                                            <label
                                                                                                class="custom-file-label"
                                                                                                for="exampleInputFile"></label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-10">
                                                                                    <label for="name">ชื่อ</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        name="name" id="name"
                                                                                        autocomplete="off"
                                                                                        placeholder="ชื่อ"
                                                                                        value="{{ $plan->name }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label
                                                                                        for="area">พื้นที่เริ่มต้น</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="text"
                                                                                            class="form-control col-md-10"
                                                                                            name="area" id="area"
                                                                                            placeholder="พื้นที่เริ่มต้น"
                                                                                            autocomplete="off"
                                                                                            value="{{ $plan->area }}">
                                                                                        <label for="area"
                                                                                            class="col-md-2">ตร.ม.</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="bed_room">จำนวน
                                                                                        ห้องนอน</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="number"
                                                                                            class="form-control col-md-10"
                                                                                            name="bed_room" id="bed_room"
                                                                                            placeholder="จำนวน ห้องนอน"
                                                                                            autocomplete="off"
                                                                                            value="{{ $plan->bed_room }}">
                                                                                        <label for="bed_room"
                                                                                            class="col-md-2">ห้อง</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="bath_room">จำนวน
                                                                                        ห้องน้ำ</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="number"
                                                                                            class="form-control col-md-10"
                                                                                            name="bath_room"
                                                                                            id="bath_room"
                                                                                            placeholder="จำนวน ห้องน้ำ"
                                                                                            autocomplete="off"
                                                                                            value="{{ $plan->bath_room }}">
                                                                                        <label for="bath_room"
                                                                                            class="col-md-2">ห้อง</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="living_room">จำนวน
                                                                                        ห้องนั่งเล่น</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="number"
                                                                                            class="form-control col-md-10"
                                                                                            name="living_room"
                                                                                            id="living_room"
                                                                                            placeholder="จำนวน ห้องนั่งเล่น"
                                                                                            autocomplete="off"
                                                                                            value="{{ $plan->living_room }}">
                                                                                        <label for="living_room"
                                                                                            class="col-md-2">ห้อง</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <div class="col-md-12">
                                                                                    <label for="kitchen_room">จำนวน
                                                                                        ห้องครัว</label>
                                                                                    <div class="form-inline">

                                                                                        <input type="number"
                                                                                            class="form-control col-md-10"
                                                                                            name="kitchen_room"
                                                                                            id="kitchen_room"
                                                                                            placeholder="จำนวน ห้องครัว"
                                                                                            autocomplete="off"
                                                                                            value="{{ $plan->kitchen_room }}">
                                                                                        <label for="kitchen_room"
                                                                                            class="col-md-2">ห้อง</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-12 text-center">

                                                                                <button type="submit"
                                                                                    class="btn btn-success"><i
                                                                                        class="fa fa-save"></i>
                                                                                    อัพเดท</button>

                                                                                <button type="button" data-toggle="modal"
                                                                                    data-target="#deleteConfirmationModal{{ $plan->id }}"
                                                                                    class="btn btn-danger delete-plan">
                                                                                    <i class="fa fa-trash">
                                                                                    </i>
                                                                                    ลบ </button>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>


                                                        </div>

                                                        <div class="modal fade"
                                                            id="deleteConfirmationModal{{ $plan->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="deleteConfirmationModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form action="{{ route('project.destroy.plan') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" value="{{ $plan->id }}"
                                                                        name="plan_id">
                                                                    <input type="hidden"
                                                                        value="{{ $dataLoginUser['user_id'] }}" name="user_id">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="deleteConfirmationModalLabel">
                                                                                ยืนยันการลบ</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            คุณแน่ใจหรือไม่ที่ต้องการลบข้อมูลนี้?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">ยกเลิก</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">ยืนยันการลบ</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    {{-- <div class="col-md-4">
                                                        <div class="card card-primary">
                                                            <div class="card-header">
                                                                <h4 class="card-title w-100">
                                                                    <a class="d-block w-100" data-toggle="collapse"
                                                                        href="#collapseOne2">
                                                                        2 Bedroom
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapseOne2" class="collapse"
                                                                data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="form-group row justify-content-center">

                                                                        <img src="{{ url('uploads/noimage.jpg') }}"
                                                                            class="z-depth-1-half "
                                                                            alt="example placeholder avatar">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label for="name">ชื่อ</label>
                                                                            <input type="text" class="form-control"
                                                                                name="name" id="name339"
                                                                                placeholder="ชื่อ"
                                                                                value="{{ $project->name }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label for="area">พื้นที่เริ่มต้น</label>
                                                                            <div class="form-inline">

                                                                                <input type="text"
                                                                                    class="form-control col-md-11"
                                                                                    name="area" id="area339"
                                                                                    placeholder="พื้นที่เริ่มต้น"
                                                                                    value="">
                                                                                <label for="area"
                                                                                    class="col-md-1">ตร.ม.</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label for="bed_room">จำนวน ห้องนอน</label>
                                                                            <div class="form-inline">

                                                                                <input type="number"
                                                                                    class="form-control col-md-11"
                                                                                    name="bed_room" id="bed_room339"
                                                                                    placeholder="จำนวน ห้องนอน"
                                                                                    value="">
                                                                                <label for="bed_room"
                                                                                    class="col-md-1">ห้อง</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label for="bath_room">จำนวน ห้องน้ำ</label>
                                                                            <div class="form-inline">

                                                                                <input type="number"
                                                                                    class="form-control col-md-11"
                                                                                    name="bath_room" id="bath_room339"
                                                                                    placeholder="จำนวน ห้องน้ำ"
                                                                                    value="">
                                                                                <label for="bath_room"
                                                                                    class="col-md-1">ห้อง</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label for="living_room">จำนวน
                                                                                ห้องนั่งเล่น</label>
                                                                            <div class="form-inline">

                                                                                <input type="number"
                                                                                    class="form-control col-md-11"
                                                                                    name="living_room" id="living_room339"
                                                                                    placeholder="จำนวน ห้องนั่งเล่น"
                                                                                    value="">
                                                                                <label for="living_room"
                                                                                    class="col-md-1">ห้อง</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label for="kitchen_room">จำนวน
                                                                                ห้องครัว</label>
                                                                            <div class="form-inline">

                                                                                <input type="number"
                                                                                    class="form-control col-md-11"
                                                                                    name="kitchen_room"
                                                                                    id="kitchen_room339"
                                                                                    placeholder="จำนวน ห้องครัว"
                                                                                    value="">
                                                                                <label for="kitchen_room"
                                                                                    class="col-md-1">ห้อง</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}

                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                                <!-- รายละเอียดราคา -->
                                <div class="tab-pane fade" id="custom-tabs-one-price" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-settings-tab">
                                    <h5 class="m-0">รายละเอียดราคา {{ $project->name }}
                                        <div class="float-right">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modal-file">
                                                <i class="fa fa-plus"></i> เพิ่มข้อมูล
                                            </button>


                                        </div>
                                    </h5>
                                    <br>
                                    <div class="col-md-12">
                                        <table class="table table-bordered text-center table-sm">
                                            <thead>
                                                <tr>
                                                    <td>#</td>
                                                    <td>วันที่อัพโหลด</td>
                                                    <td>วันหมดอายุ</td>
                                                    <td>File</td>
                                                    <td>หมายเหตุ</td>
                                                    <td>Action</td>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($files as $file)
                                                    <tr>
                                                        <td width="5%">{{ $loop->index + 1 }}</td>
                                                        <td width="15%">
                                                            <h6>
                                                                <span id="badge"
                                                                    class="badge rounded-pill bg-success">{{ date('d/m/Y', strtotime($file->created_at)) }}</span>
                                                            </h6>
                                                        </td>
                                                        <td width="15%">
                                                            <h6> <span id="badge"
                                                                    class="badge rounded-pill bg-danger">{{ date('d/m/Y', strtotime($file->exp)) }}</span>
                                                            </h6>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($file->file)
                                                                <a href="{{ $file->file }}" target="_blank"
                                                                    class="mailbox-attachment-name"><i
                                                                        class="far fa-file-pdf"></i>
                                                                    @php
                                                                        $nameFile = basename(parse_url($file->file, PHP_URL_PATH));
                                                                    @endphp

                                                                    {{ $nameFile }}</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td width="15%" class="">{{ $file->remark }}</td>
                                                        <td class="text-center" width="15%">

                                                            <button class="btn bg-gradient-info btn-sm show-item-price"
                                                                data-toggle="tooltip" data-placement="top" title="แก้ไข"
                                                                data-id="{{ $file->id }}"><i
                                                                    class="fa fa-pencil-square"></i> แก้ไข
                                                            </button>


                                                            <button
                                                                class="btn bg-gradient-danger  btn-sm delete-item-price"
                                                                data-toggle="tooltip" data-placement="top" title="ลบ"
                                                                data-id="{{ $file->id }}"><i class="fa fa-trash"></i>
                                                                ลบ
                                                            </button>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <!-- สัญญา -->
                                <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-settings-tab">
                                    <h5 class="m-0">สัญญา {{ $project->name }}
                                        <div class="float-right">
                                            @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin' || $isRole->dept == 'Legal')
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#modal-xl2">
                                                    <i class="fa fa-plus"></i> เพิ่ม สัญญา
                                                </button>
                                            @endif

                                        </div>
                                    </h5>
                                    <br>
                                    <div class="col-md-12">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <td>#</td>
                                                    <td>วันที่เริ่มสัญญา</td>
                                                    <td>วันที่สิ้นสุดสัญญา</td>
                                                    <td>จำนวนห้อง</td>
                                                    <td>มัดจำ</td>
                                                    <td>คงเหลือ</td>
                                                    <td>หมายเหตุ</td>
                                                    <td>Action</td>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($contracts as $contact)
                                                    <tr>
                                                        <td width="5%">{{ $loop->index + 1 }}</td>
                                                        <td width="12%">
                                                            {{ date('d/m/Y', strtotime($contact->start_date)) }}</td>
                                                        <td width="12%">
                                                            {{ date('d/m/Y', strtotime($contact->end_date)) }}</td>
                                                        <td class="text-right">{{ number_format($contact->room) }}
                                                        </td>
                                                        <td class="text-right">{{ number_format($contact->deposit) }}
                                                        </td>
                                                        <td class="text-right">{{ number_format($contact->amount) }}
                                                        </td>
                                                        <td width="20%">{{ $contact->note }}</td>
                                                        <td class="text-center">
                                                            @if ($isRole->role_type == 'SuperAdmin' || $isRole->role_type == 'Admin')
                                                                <button class="btn bg-gradient-info btn-sm edit-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="แก้ไข" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-pencil-square"></i> แก้ไข
                                                                </button>

                                                                <button class="btn bg-gradient-info btn-sm detail-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="รายละเอียด" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-th-list"></i> รายละเอียด
                                                                </button>

                                                                <button class="btn bg-gradient-info btn-sm deposit-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="คืนมัดจำ" data-id="{{ $contact->id }}">
                                                                    <i class="fa fa-reply"></i> คืนมัดจำ
                                                                </button>

                                                                <button class="btn bg-gradient-danger  btn-sm delete-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="ลบ" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-trash"></i>
                                                                    ลบ
                                                                </button>
                                                            @elseif ($isRole->role_type == 'User' && $isRole->dept == 'Legal')
                                                                <button class="btn bg-gradient-info btn-sm edit-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="แก้ไข" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-pencil-square"></i> แก้ไข
                                                                </button>

                                                                <button class="btn bg-gradient-info btn-sm detail-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="รายละเอียด" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-th-list"></i> รายละเอียด
                                                                </button>

                                                                <button class="btn bg-gradient-info btn-sm deposit-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="คืนมัดจำ" data-id="{{ $contact->id }}">
                                                                    <i class="fa fa-reply"></i> คืนมัดจำ
                                                                </button>

                                                                <button class="btn bg-gradient-danger  btn-sm delete-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="ลบ" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-trash"></i>
                                                                    ลบ
                                                                </button>
                                                            @elseif ($isRole->role_type == 'User' && $isRole->dept == 'Audit')
                                                                <button class="btn bg-gradient-info btn-sm detail-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="รายละเอียด" data-id="{{ $contact->id }}"><i
                                                                        class="fa fa-th-list"></i> รายละเอียด
                                                                </button>
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

                    </div>
                </div>

                <!-- The Modal เพิ่มสัญญา -->
                <div class="modal fade" id="modal-xl2">
                    <div class="modal-dialog modal-md">
                        <form id="createContract" name="createContract" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">สัญญา</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date">วันที่เริ่มสัญญา</label>
                                            <input type="text" class="form-control datepicker" name="start_date"
                                                id="start_date" placeholder="วันที่เริ่มสัญญา" value=""
                                                autocomplete="off" required>
                                            <p class="text-danger mt-1 start_date_err"></p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="end_date">วันที่สิ้นสุดสัญญา</label>
                                            <div class="form-inline">

                                                <input type="text" class="form-control col-md-12 datepicker"
                                                    name="end_date" id="end_date" placeholder="วันที่สิ้นสุดสัญญา"
                                                    value="" autocomplete="off" required>
                                                <p class="text-danger mt-1 end_date_err"></p>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="room">จำนวนห้อง</label>
                                            <div class="form-inline">
                                                <input type="number" class="form-control col-md-12" name="room"
                                                    id="room" placeholder="จำนวนห้อง" value=""
                                                    autocomplete="off" required>
                                                <p class="text-danger mt-1 room_err"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">ค่ามัดจำ</label>
                                            <div class="form-inline">
                                                <input type="number" class="form-control col-md-12" name="deposit"
                                                    id="deposit" placeholder="ค่ามัดจำ" value=""
                                                    autocomplete="off" required>
                                                <p class="text-danger mt-1 deposit_err"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">หมายเหตุ</label>
                                            <div class="form-inline">
                                                <textarea name="note" id="note" class="form-control col-md-12" cols="30" rows="10"
                                                    autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fa fa-times"></i> ออก</button>
                                        <button type="button" class="btn btn-success" id="savedataContract"
                                            value="create"><i class="fa fa-save"></i>
                                            บันทึก</button>

                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ $dataLoginUser['user_id'] }}">
                                        <input type="hidden" name="project_id" id="project_id"
                                            value="{{ $project->id }}">

                                    </div>


                                </div>



                            </div>
                        </form>
                    </div>
                </div><!-- Modal footer เพิ่มสัญญา -->

                <!-- The Modal ห้องชุด-->
                <div class="modal fade" id="modal-xl1">
                    <div class="modal-dialog modal-lg">
                        <form id="createPlan" name="createPlan" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">เพิ่มห้องชุด</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group row justify-content-center">

                                        <div class="col-md-12 text-center">

                                            <img src="{{ url('uploads/noimage.jpg') }}" id="img-show-addplan"
                                                class="size-image" alt="">

                                            <div class="form-group">
                                                <br>
                                                <label for="exampleInputFile">รูปภาพห้องชุด </label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            onchange="previewImageaddplan(event)" accept="image/jpeg"
                                                            id="plan_image" name="plan_image">
                                                        <label class="custom-file-label" for="exampleInputFile"></label>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">ชื่อห้องชุด</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="ชื่อห้องชุด" value="" autocomplete="off">
                                                <p class="text-danger mt-1 name_err"></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="area">พื้นที่เริ่มต้น (ตร.ม.)</label>
                                                <input type="text" class="form-control" id="area" name="area"
                                                    placeholder="พื้นที่เริ่มต้น" value="" autocomplete="off">
                                                <p class="text-danger mt-1 area_err"></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="bed_room">จำนวน ห้องนอน</label>
                                                <input type="number" class="form-control" id="bed_room"
                                                    name="bed_room" placeholder="จำนวน ห้องนอน" value=""
                                                    autocomplete="off">
                                                <p class="text-danger mt-1 bed_room_err"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bath_room">จำนวน ห้องน้ำ</label>
                                                <input type="number" class="form-control" id="bath_room"
                                                    name="bath_room" placeholder="จำนวน ห้องน้ำ" value=""
                                                    autocomplete="off">
                                                <p class="text-danger mt-1 bath_room_err"></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="living_room">จำนวน ห้องนั่งเล่น</label>
                                                <input type="number" class="form-control" id="living_room"
                                                    name="living_room" placeholder="จำนวน ห้องนั่งเล่น" value=""
                                                    autocomplete="off">
                                                <p class="text-danger mt-1 living_room_err"></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="kitchen_room">จำนวน ห้องครัว</label>
                                                <input type="number" class="form-control" id="kitchen_room"
                                                    name="kitchen_room" placeholder="จำนวน ห้องครัว" value=""
                                                    autocomplete="off">
                                                <p class="text-danger mt-1 kitchen_room_err"></p>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                            class="fa fa-times"></i> ออก</button>
                                    <button type="button" class="btn btn-success" id="savedataPlan" value="create"><i
                                            class="fa fa-save"></i>
                                        บันทึก</button>

                                    <input type="hidden" name="user_id" id="user_id"
                                        value="{{ $dataLoginUser['user_id'] }}">
                                    <input type="hidden" name="project_id" id="project_id"
                                        value="{{ $project->id }}">

                                </div>
                            </div>
                        </form>
                        <!-- Modal footer -->
                    </div>
                </div>


                <!-- The Modal Floor-->
                <div class="modal fade" id="modal-xl">
                    <div class="modal-dialog modal-lg">
                        <form id="createFloor" name="createFloor" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">เพิ่ม Floor</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="form-group row justify-content-center">
                                        <div class="form-group">
                                            <img src="{{ url('uploads/noimage.jpg') }}" id="img-show-addfloor"
                                                class="size-image" alt="">
                                            <br>
                                            <label for="exampleInputFile">รูปภาพ Floor </label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input"
                                                        onchange="previewImageaddfloor(event)" accept="image/jpeg"
                                                        id="image" name="image">
                                                    <label class="custom-file-label" for="exampleInputFile"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">ชื่อ Floor</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    placeholder="ชื่อ Floor" value="" autocomplete="off">
                                                <p class="text-danger mt-1 name_err"></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="building">ตึก</label>
                                                <input type="text" class="form-control" id="building"
                                                    name="building" placeholder="ตึก" value="" autocomplete="off">
                                                <p class="text-danger mt-1 building_err"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">ชั้น</label>
                                                <input type="number" class="form-control" id="floor" name="floor"
                                                    placeholder="ชั้น" value="" autocomplete="off">
                                                <p class="text-danger mt-1 floor_err"></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="rooms">จำนวนห้อง</label>
                                                <input type="number" class="form-control" id="rooms" name="rooms"
                                                    placeholder="จำนวนห้อง" value="" autocomplete="off">
                                                <p class="text-danger mt-1 rooms_err"></p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                            class="fa fa-times"></i> ออก</button>
                                    <button type="button" class="btn btn-success" id="savedataFloor" value="create"><i
                                            class="fa fa-save"></i>
                                        บันทึก</button>

                                    <input type="hidden" name="user_id" id="user_id"
                                        value="{{ $dataLoginUser['user_id'] }}">
                                    <input type="hidden" name="project_id" id="project_id"
                                        value="{{ $project->id }}">

                                </div>
                                <!-- Modal footer -->
                            </div>
                        </form>
                    </div>
                </div> <!-- The Modal Floor-->


                <!-- edit contract-->
                <div class="modal fade" id="modal-edit-contract">
                    <div class="modal-dialog modal-md">
                        <form id="editContract" name="editContract" class="form-horizontal">
                            @csrf
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">สัญญา</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date">วันที่เริ่มสัญญา</label>
                                            <input type="text" class="form-control datepicker" name="start_date_edit"
                                                id="start_date_edit" placeholder="วันที่เริ่มสัญญา" value=""
                                                autocomplete="off" required>
                                            <p class="text-danger mt-1 start_date_edit_err"></p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="end_date">วันที่สิ้นสุดสัญญา</label>
                                            <div class="form-inline">

                                                <input type="text" class="form-control col-md-12 datepicker"
                                                    name="end_date_edit" id="end_date_edit"
                                                    placeholder="วันที่สิ้นสุดสัญญา" value="" autocomplete="off"
                                                    required>
                                                <p class="text-danger mt-1 end_date_edit_err"></p>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="room">จำนวนห้อง</label>
                                            <div class="form-inline">
                                                <input type="number" class="form-control col-md-12" name="room_edit"
                                                    id="room_edit" placeholder="จำนวนห้อง" value=""
                                                    autocomplete="off" required>
                                                <p class="text-danger mt-1 room_edit_err"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">ค่ามัดจำ</label>
                                            <div class="form-inline">
                                                <input type="number" class="form-control col-md-12"
                                                    name="deposit_edit" id="deposit_edit" placeholder="ค่ามัดจำ"
                                                    value="" autocomplete="off" required>
                                                <p class="text-danger mt-1 deposit_edit_err"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">หมายเหตุ</label>
                                            <div class="form-inline">
                                                <textarea name="note_edit" id="note_edit" class="form-control col-md-12" cols="30" rows="10"
                                                    autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fa fa-times"></i> ออก</button>
                                        <button type="button" class="btn btn-success" id="updatedatacontract"
                                            value="update"><i class="fa fa-save"></i>
                                            บันทึก</button>

                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ $dataLoginUser['user_id'] }}">
                                        <input type="hidden" name="project_id" id="project_id"
                                            value="{{ $project->id }}">
                                        <input type="hidden" class="form-control" id="id_edit" name="id_edit"
                                            placeholder="" autocomplete="off" value="">

                                    </div>


                                </div>



                            </div>
                        </form>
                    </div>
                </div>

                <!-- edtail contract-->
                <div class="modal fade" id="modal-detail-contract">
                    <div class="modal-dialog modal-lg">

                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">รายละเอียด</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">



                                <table class="table table-sm table-bordered" id="tb-detail-contract">
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>วันที่</th>
                                            <th>มัดจำ</th>
                                            <th>จำนวนห้อง</th>
                                            <th>สถานะ</th>
                                            <th>หมายเหตุ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>










                            </div>



                        </div>

                    </div>
                </div>

                <!-- edit contract-->
                <div class="modal fade" id="modal-deposit">
                    <div class="modal-dialog modal-md">
                        <form id="editDeposite" name="editDeposite" class="form-horizontal">
                            @csrf
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">คืนมัดจำ</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date">วันที่</label>
                                            <input type="text" class="form-control datepicker" name="date_deposit"
                                                id="date_deposit" placeholder="วันที่" value=""
                                                autocomplete="off" required>
                                            <p class="text-danger mt-1 date_deposit_err"></p>
                                        </div>
                                    </div>




                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="room">จำนวนห้อง</label>
                                            <div class="form-inline">
                                                <input type="number" class="form-control col-md-12"
                                                    name="room_deposit" id="room_deposit" placeholder="จำนวนห้อง"
                                                    value="" autocomplete="off" required>
                                                <p class="text-danger mt-1 room_deposit_err"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">ค่ามัดจำ</label>
                                            <div class="form-inline">
                                                <input type="number" class="form-control col-md-12" name="deposit"
                                                    id="deposit" placeholder="ค่ามัดจำ" value=""
                                                    autocomplete="off" required>
                                                <p class="text-danger mt-1 deposit_err"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">สถานะ</label>
                                            <div class="form-inline">
                                                <select class="form-control col-md-12" id="status" name="status">
                                                    <option value="">เลือก</option>
                                                    <option value="1">ได้รับเงินมัดจำแล้ว</option>
                                                    <option value="2">ยังไม่ได้รับเงินมัดจำ</option>

                                                </select>
                                                <p class="text-danger mt-1 status_err"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">หมายเหตุ</label>
                                            <div class="form-inline">
                                                <textarea name="note_deposit" id="note_deposit" class="form-control col-md-12" cols="30" rows="10"
                                                    autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fa fa-times"></i> ออก</button>
                                        <button type="button" class="btn btn-success" id="updatedeposit"
                                            value="update"><i class="fa fa-save"></i>
                                            บันทึก</button>

                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ $dataLoginUser['user_id'] }}">

                                        <input type="hidden" class="form-control" id="p_id" name="p_id"
                                            placeholder="" autocomplete="off" value="">
                                        <input type="hidden" class="form-control" id="contract_id"
                                            name="contract_id" placeholder="" autocomplete="off" value="">

                                    </div>


                                </div>



                            </div>
                        </form>
                    </div>
                </div>


                <!-- Add File-->
                <div class="modal fade" id="modal-file">
                    <div class="modal-dialog modal-md">
                        <form id="createFile" name="createFile" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">อัพโหลดไฟล์</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date"><span class="text-danger">*</span> PDF File</label>
                                            <div class="custom-file">
                                                <input type="file" class="" name="file" id="file"
                                                    accept=".pdf">
                                            </div>

                                        </div>
                                        <p class="text-danger mt-1 file_err"></p>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date"><span class="text-danger">*</span> วันหมดอายุ Expire
                                                Date</label>
                                            <input type="text" class="form-control datepicker" name="exp"
                                                id="exp" value="" autocomplete="off">
                                            <p class="text-danger mt-1 exp_err"></p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">หมายเหตุ</label>
                                            <div class="form-inline">
                                                <textarea name="remark" id="remark" class="form-control col-md-12" cols="15" rows="5"
                                                    autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">ระบุอีเมล์ที่ต้องการให้แจ้งเตือน
                                                เมื่อไฟล์ใกล้หมดอายุ</label>
                                            <div id="emailContainer">
                                                <div class="input-group mb-2">
                                                    <input type="email" class="form-control" name="email_alert[]"
                                                        placeholder="test.t@vbeyond.co.th">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-success"
                                                            onclick="addEmailInput()">+</button>
                                                    </div>
                                                </div>
                                                <p class="text-danger mt-1 email_err"></p>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fa fa-times"></i> ออก</button>
                                        <button type="button" class="btn btn-success" id="savedataFile"
                                            value="save"><i class="fa fa-save"></i>
                                            บันทึก</button>

                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ $dataLoginUser['user_id'] }}">
                                        <input type="hidden" name="project_id" id="project_id"
                                            value="{{ $project->id }}">

                                    </div>

                                </div>


                            </div>
                        </form>
                    </div>
                </div>
                <!-- Update File-->
                <div class="modal fade" id="modal-file-edit">
                    <div class="modal-dialog modal-md">
                        <form id="updateFile" name="updateFile" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">อัพเดทไฟล์ข้อมูล</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date">PDF File</label>
                                            <div class="custom-file">
                                                <input type="file" class="" name="file_edit" id="file_edit"
                                                    accept=".pdf" required>
                                            </div>

                                        </div>
                                        <p class="text-danger mt-1 exp_file_err"></p>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="start_date">วันหมดอายุ Expire Date</label>
                                            <input type="text" class="form-control datepicker" name="exp_edit"
                                                id="exp_edit" placeholder="Expire Date" value=""
                                                autocomplete="off" required>
                                            <p class="text-danger mt-1 exp_edit_err"></p>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="deposit">หมายเหตุ</label>
                                            <div class="form-inline">
                                                <textarea name="remark_edit" id="remark_edit" class="form-control col-md-12" cols="15" rows="5"
                                                    autocomplete="off"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label for="deposit">ระบุอีเมล์ที่ต้องการให้แจ้งเตือน เมื่อไฟล์ใกล้หมดอายุ</label>
                                                    <div id="emailContainerEdit">

                                                        <div class="input-group mb-2">
                                                            <input type="email" class="form-control" name="email_edit[]" placeholder="test.t@vbeyond.co.th">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-success add-email" onclick="addEmailInputEdit()">+</button>
                                                            </div>
                                                        </div>
                                                        <p class="text-danger mt-1 email_err"></p>
                                                    </div>
                                                    <div id="emailContainerEdit2">
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>



                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i
                                                class="fa fa-times"></i> ออก</button>
                                        <button type="button" class="btn btn-success" id="updatefile"
                                            value="save"><i class="fa fa-save"></i>
                                            บันทึก</button>

                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ $dataLoginUser['user_id'] }}">
                                        <input type="hidden" name="project_id" id="project_id"
                                            value="{{ $project->id }}">
                                        <input type="hidden" name="id_edit" id="id_edit" value="">

                                    </div>

                                </div>


                            </div>
                        </form>
                    </div>
                </div>


            </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        function addEmailInput() {
            var container = document.getElementById('emailContainer');
            var newInput = document.createElement('div');
            newInput.className = 'input-group mb-2';
            newInput.innerHTML =
                '<input type="email" class="form-control" name="email_alert[]" placeholder="test.t@vbeyond.co.th">' +
                '<div class="input-group-append">' +
                '<button type="button" class="btn btn-danger" onclick="removeEmailInput(this)">-</button>' +
                '</div>';
            container.appendChild(newInput);
        }

        function removeEmailInput(button) {
            var container = document.getElementById('emailContainer');
            container.removeChild(button.parentNode.parentNode);
        }
        function addEmailInputEdit() {
            var container = document.getElementById('emailContainerEdit');
            var newInput = document.createElement('div');
            newInput.className = 'input-group mb-2';
            newInput.innerHTML =
                '<input type="email" class="form-control" name="email_edit[]" placeholder="test.t@vbeyond.co.th">' +
                '<div class="input-group-append">' +
                '<button type="button" class="btn btn-danger" onclick="removeEmailInput2(this)">-</button>' +
                '</div>';
            container.appendChild(newInput);
        }

        function removeEmailInput2(button) {
            var container = document.getElementById('emailContainerEdit');
            container.removeChild(button.parentNode.parentNode);
        }
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
                todayHighlight: true
            });

            $('#start_date').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#end_date').datepicker('setStartDate', selectedStartDate);
            });

            $('#start_date_edit').on('changeDate', function(e) {
                var selectedStartDatee = e.date;
                $('#end_date_edit').datepicker('setStartDate', selectedStartDatee);
            });

            const user_id = {{ $dataLoginUser['user_id'] }};

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



            //Save plan
            $('#savedataPlan').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');


                const _token = $("input[name='_token']").val();
                const plan_image = $("#plan_image").val();
                const name = $("#name").val();
                const area = $("#area").val();
                const bed_room = $("#bed_room").val();
                const bath_room = $("#bath_room").val();
                const living_room = $("#living_room").val();
                const kitchen_room = $("#kitchen_room").val();

                //console.log("Data being sent: " + $('#createPlan').serialize());
                //console.log(plan_image);
                const formData = new FormData($('#createPlan')[0]);
                formData.append('plan_image', $('#plan_image')[0].files[0]);

                $.ajax({

                    //data: $('#createPlan').serialize(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('project.store.plan') }}",
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

                                $('#createPlan')[0].reset();
                                $('#modal-xl1').modal('hide');

                                // table.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedataPlan').html('ลองอีกครั้ง');
                                $('#createPlan').trigger("reset");
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

                });
            });

            //Save Floor
            $('#savedataFloor').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');


                const _token = $("input[name='_token']").val();
                const image = $("#image").val();
                const name = $("#name").val();
                const building = $("#building").val();
                const floor = $("#floor").val();
                const rooms = $("#rooms").val();
                const formData = new FormData($('#createFloor')[0]);
                formData.append('image', $('#image')[0].files[0]);

                $.ajax({

                    //data: $('#createFloor').serialize(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('project.store.floor') }}",
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

                                $('#createFloor')[0].reset();
                                $('#modal-xl1').modal('hide');

                                // table.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#savedataFloor').html('ลองอีกครั้ง');
                                $('#createFloor').trigger("reset");
                                $('.name_err').text(data.error.name);
                                $('.building_err').text(data.error.building);
                                $('.floor_err').text(data.error.floor);
                                $('.rooms_err').text(data.error.rooms);
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

            //Save Contract
            $('#savedataContract').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');


                const _token = $("input[name='_token']").val();

                const start_date = $("#start_date").val();
                const end_date = $("#end_date").val();
                const room = $("#room").val();
                const deposit = $("#deposit").val();
                const note = $("#note").val();

                //console.log("Data being sent: " + $('#createPlan').serialize());
                //console.log(plan_image);
                const formData = new FormData($('#createContract')[0]);

                $.ajax({

                    //data: $('#createPlan').serialize(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('contract.store') }}",
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

                                $('#createContract')[0].reset();
                                $('#modal-xl2').modal('hide');

                                // table.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);

                            } else {

                                printErrorMsg3(data.error);
                                $('#savedataContract').html('ลองอีกครั้ง');
                                $('#createContact').trigger("reset");
                                $('.start_date').text(data.error.start_date);
                                $('.end_date').text(data.error.end_date);
                                $('.room').text(data.error.room);
                                $('.deposit').text(data.error.deposit);
                                $('.note').text(data.error.note);
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

            //Save File
            $('#savedataFile').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');


                const _token = $("input[name='_token']").val();
                const exp = $("#exp").val();
                // const email_alert = $("#email_alert").val();
                const project_id = $("#project_id").val();
                const created_by = $("#created_by").val();
                const remark = $("#remark").val();

                const formData = new FormData($('#createFile')[0]);
                formData.append('file', $('#file')[0].files[0]);

                $.ajax({

                    //data: $('#createPlan').serialize(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('project.store.fileprice') }}",
                    type: "POST",
                    dataType: "json",

                    success: function(data) {
                        console.log(data);
                        if (data.success = true) {

                            if ($.isEmptyObject(data.error)) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                });

                                $('#createFile')[0].reset();
                                $('#modal-file').modal('hide');

                                // table.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);

                            } else {

                                printErrorMsg(data.error);
                                // console.log(data.error);
                                $('#savefile').html('ลองอีกครั้ง');
                                $('#createFile').trigger("reset");
                                $('.name_err').text(data.error.name);
                                $('.file_err').text(data.error.file);
                                if (data.error.email_alert && data.error.email_alert.length > 0) {
                                    $('.email_err').text(data.error.email_alert[0]);
                                } else {
                                    $('.email_err').text('กรุณากรอกอีเมล์');
                                }

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
                            url: '/api/contract/destroy/' + id + '/' + user_id,
                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'ลบข้อมูลสำเร็จ !',
                                    showConfirmButton: true,
                                    timer: 2500
                                });

                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" +
                                        data.id;
                                }, 1500);

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

            //Del Fileprice
            $('body').on('click', '.delete-item-price', function() {
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
                            url: '/api/project/fileprice/destroy/' + id + '/' + user_id,
                            success: function(data) {

                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                });

                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" +
                                        data.id;
                                }, 1500);

                            },
                        });
                    }
                });

            });


        // Use the click event inside the document.ready or another appropriate scope

            $('body').on('click', '.show-item-price', function () {
                const id = $(this).data('id');

                $('#modal-file-edit').modal('show');

                $.get('/api/project/fileprice/edit/' + id, function (data) {
                    // Clear previous email inputs
                    $('#emailContainerEdit2').empty();
                    console.log(data);
                    $('#exp_edit').val(data.exp);
                    $('#remark_edit').val(data.remark);
                    $('#id_edit').val(data.id);
                    // Populate email inputs
                    if (data.email_alert && data.email_alert.length > 0) {
                        for (let i = 0; i < data.email_alert.length; i++) {
                            removeEmailInputEdit2(data.email_alert[i]);
                            //console.log(data.email_alert[i]);
                        }
                    } else {
                        // If no emails are present, add one input field by default
                        removeEmailInputEdit2('');
                    }
                });
            });

            function removeEmailInputEdit2(emailValue) {
                var container = $('#emailContainerEdit2');

                var newInput = $('<div class="input-group mb-2">' +
                    `<input type="email" class="form-control" name="email_edit[]" value="${emailValue}" placeholder="test.t@vbeyond.co.th">` +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-danger remove-email">-</button>' +
                    '</div>' +
                    '</div>');

                container.append(newInput);

                newInput.find('.remove-email').click(function() {
                    $(this).closest('.input-group').remove();
                });
            }




            $('#updatefile').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const id = $("#id_edit").val();
                const edit = $("#exp_edit").val();
                const file = $("#file_edit").val();

                const formData = new FormData($('#updateFile')[0]);
                formData.append('file_edit', $('#file_edit')[0].files[0]);
                //console.log(id);
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: '/api/project/fileprice/update/' + id,
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
                                $('#updateFile').trigger("reset");
                                $('#modal-file-edit').modal('hide');
                                //tableUser.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);
                            } else {
                                printErrorMsg2(data.error);
                                $('.exp_edit_err').text(data.error.exp_edit);
                                $('.exp_file_err').text(data.error.file_edit);
                                $('#modal-file-edit').trigger("reset");
                                $('#updatefile').html('ลองอีกครั้ง');

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
                            $('#updateFile').trigger("reset");
                        }


                    },

                });
            });


            //ShowEdit
            $('body').on('click', '.edit-item', function() {

                const id = $(this).data('id');
                $('#modal-edit-contract').modal('show');

                $.get('/api/contract/edit/' + id, function(data) {
                    //console.log(data);
                    $('#id_edit').val(data.id);
                    $('#start_date_edit').val(data.start_date);
                    $('#end_date_edit').val(data.end_date);
                    $('#room_edit').val(data.room);
                    $('#deposit_edit').val(data.deposit);
                    $('#note_edit').val(data.note);
                });
            });

            //Show Detail Contract
            $('body').on('click', '.detail-item', function() {

                const id = $(this).data('id');
                //console.log(id);
                $('#modal-detail-contract').modal('show');

                $.get('/api/contract/detail/' + id, function(data) {
                    // ลบข้อมูลเก่าทั้งหมดใน tbody
                    $('#tb-detail-contract tbody').empty();
                    //console.log(data);
                    if (data.length === 0) {
                        // ถ้าไม่พบข้อมูลให้แสดงข้อความ
                        $('#tb-detail-contract tbody').append(`
                            <tr>
                                <td colspan="6" class="text-center">ไม่พบข้อมูล</td>
                            </tr>
                        `);
                    } else {
                        // ถ้ามีข้อมูลให้แสดงข้อมูลในตาราง
                        $.each(data, function(index, contract) {
                            const formattedDate = moment(contract.created_at).format(
                                'D/MM/Y');
                            const statusText = contract.status === 1 ?
                                'ได้รับเงินมัดจำแล้ว' : 'ยังไม่ได้รับเงินมัดจำ';
                            $('#tb-detail-contract tbody').append(`
                                <tr class="text-center">
                                    <td>${index + 1}</td>
                                    <td>${formattedDate}</td>
                                    <td>${contract.deposit}</td>
                                    <td>${contract.room_amount}</td>
                                    <td>${statusText}</td>
                                    <td>${contract.note}</td>
                                </tr>
                            `);
                        });
                    }
                });

            });

            //updateData
            $('#updatedatacontract').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const id = $("#id_edit").val();
                const start_date_edit = $("#start_date_edit").val();
                const end_date_edit = $("#end_date_edit").val();
                const room_edit = $("#room_edit").val();
                const deposit_edit = $("#deposit_edit").val();
                const note_edit = $("#note_edit").val();
                const user_id = $("#user_id").val();
                const project_id = $("#project_id").val();
                //console.log(id);
                $.ajax({
                    data: $('#editContract').serialize(),
                    url: '/api/contract/update/' + id,
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
                                $('#modal-edit-contract').trigger("reset");
                                $('#modal-edit-contract').modal('hide');
                                //tableUser.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);
                            } else {
                                printErrorMsg2(data.error);
                                $('.room_edit_err').text(data.error.room_edit);
                                $('.start_date_edit_err').text(data.error.start_date_edit);
                                $('.end_date_edit_err').text(data.error.end_date_edit);
                                $('.deposit_edit_err').text(data.error.deposit_edit);
                                $('#modal-edit-contract').trigger("reset");
                                $('#updatedatacontract').html('ลองอีกครั้ง');

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
                            $('#editContract').trigger("reset");
                        }


                    },

                });
            });

            //Show FormDeposit
            $('body').on('click', '.deposit-item', function() {
                const id = $(this).data('id');
                $('#modal-deposit').modal('show');

                $.get('/api/contract/edit/' + id, function(data) {
                    //console.log(data.project_id);
                    $('#contract_id').val(data.id);
                    $('#p_id').val(data.project_id);


                });
            });

            $('#updatedeposit').click(function(e) {
                e.preventDefault();
                $(this).html('รอสักครู่..');
                const _token = $("input[name='_token']").val();
                const contract_id = $("#contract_id").val();
                const user_id = $("#user_id").val();
                const project_id = $("#p_id").val();
                const deposit = $("#deposit").val();
                const status = $("#status").val();
                const note_deposit = $("#note_deposit").val();
                const room_deposit = $("#room_deposit").val();
                const date_deposit = $("#date_deposit").val();
                //console.log(id);
                const formData = new FormData($('#editDeposite')[0]);
                $.ajax({
                    data: formData,
                    processData: false,
                    contentType: false,
                    url: "{{ route('contract.returndeposit') }}",
                    type: "POST",
                    dataType: "json",

                    // data: $('#editDeposite').serialize(),
                    // url: "{{ route('contract.returndeposit') }}",
                    // type: "POST",
                    // dataType: "json",

                    success: function(data) {
                        console.log(data);
                        if (data.success = true) {

                            if ($.isEmptyObject(data.error)) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                });

                                $('#editDeposite')[0].reset();
                                $('#modal-deposit').modal('hide');

                                // table.draw();
                                setTimeout(function() {
                                    window.location.href =
                                        '{{ url('/projects/edit') }}' + "/" + data.id;
                                }, 1500);

                            } else {

                                printErrorMsg(data.error);
                                $('#updatedeposit').html('ลองอีกครั้ง');
                                $('#editDeposite').trigger("reset");
                                $('.date_deposit').text(data.error.date_deposit);
                                $('.room_deposit').text(data.error.room_deposit);
                                $('.deposit').text(data.error.deposit);
                                $('.status').text(data.error.status);
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

                    // statusCode: {
                    //     400: function() {
                    //         $('#savedata').html('ลองอีกครั้ง');
                    //         $('.name_err').text("ซ้ำ");
                    //         Swal.fire({
                    //             position: 'top-center',
                    //             icon: 'error',
                    //             title: 'ชื่อเฟอรนิเจอร์ซ้ำ',
                    //             showConfirmButton: true,
                    //             timer: 1500
                    //         });
                    //     }
                    // }

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

            function printErrorMsg3(msg) {
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
    </script>

    <script>
        function previewImageaddplan(event) {
            var imageElement = document.getElementById('img-show-addplan');
            var fileInput = event.target;

            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imageElement.src = e.target.result;
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        }

        function previewImageaddfloor(event) {
            var imageElement = document.getElementById('img-show-addfloor');
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

    @foreach ($plans as $plan)
        <script>
            function previewImagePlan{{ $plan->id }}(event) {
                var imageElement = document.getElementById('img-show-{{ $plan->id }}');
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
    @endforeach

    @foreach ($floors as $floor)
        <script>
            function previewImageFloor{{ $floor->id }}(event) {
                var imageElement = document.getElementById('img-show-floor-{{ $floor->id }}');
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
    @endforeach



@endpush
