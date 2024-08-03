@extends('layouts.app')

@section('title', 'ห้องเช่า')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">ห้องเช่า</h1>
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
                        <form action="{{ route('rentral.search') }}" method="post" id="searchForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">


                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>โครงการ</label>
                                            <select name="pid" id="pid" class="form-control">
                                                <option value="all">โครงการ ทั้งหมด</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project['project_id'] }}">{{ $project['Project_Name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>สถานะห้องเช่า</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="all">สถานะห้องเช่า ทั้งหมด</option>
                                                @foreach ($status as $item)
                                                    <option value="{{ $item['status_room'] }}">{{ $item['status_room'] }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>ประเภทห้องเช่า</label>
                                            @php
                                            $typerents = ['การันตี', 'การันตีรับร่วงหน้า', 'เบิกจ่ายล่วงหน้า', 'ฝากต่อหักภาษี', 'ฝากต่อไม่หักภาษี', 'ฝากเช่า', 'ติดต่อเจ้าของห้องไม่ได้'];
                                            @endphp

                                            <select name="typerent" id="typerent" class="form-control">
                                                <option value="all">ประเภท ทั้งหมด</option>
                                                @foreach ($typerents as $typerent)
                                                    <option value="{{$typerent}}">{{$typerent}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>ห้องเลขที่</label>
                                                <input class="form-control" name="RoomNo" type="text" value=""
                                                    placeholder="ห้องเลขที่" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>บ้านเลขที่</label>
                                                <input class="form-control" name="HomeNo" type="text" value=""
                                                    placeholder="บ้านเลขที่" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>วันที่เริ่มต้น</label>
                                            <input class="form-control datepicker" name="startdate" id="startdate"
                                            type="text" value="" placeholder="วันที่เริ่มต้น" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>วันที่สิ้นสุด</label>
                                            <input class="form-control datepicker" name="enddate" id="enddate"
                                            type="text" value="" placeholder="วันที่สิ้นสุด" autocomplete="off">
                                        </div>
                                    </div>

                                </div>

                                <div class="box-footer text-center">
                                    <button type="submit" class="btn bg-gradient-success"><i
                                            class="fa fa-search"></i>
                                        ค้นหา</button>
                                    <a href="{{ route('rentral') }}" type="button"
                                        class="btn bg-gradient-danger"><i class="fa fa-refresh"></i> เคลียร์</a>
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
@endpush
