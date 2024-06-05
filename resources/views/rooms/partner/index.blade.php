@extends('layouts.app')

@section('title', 'ห้อง')

@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">ห้อง ทั้งหมด

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
                        <form action="{{ route('room.search.partner') }}" method="post" id="searchForm">
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
                                                    @isset($_GET['status'])
                                                    {{ in_array($item->id, $_GET['status']) ? 'checked' : '' }}
                                                    @else
                                                    {{ 'checked' }}
                                                    @endisset>
                                                <label for="{{ $item->id }}">{{ $item->name }}</label>
                                            @endforeach


                                            <input type="checkbox" name="status_pi" class="status" id="" value="{{$isRole->dept}}">
                                            <label for="" class="mr-2">{{$isRole->dept}}</label>

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
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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

    <script>
        document.getElementById('searchForm').addEventListener('submit', function(event) {
            const project_id = document.getElementById('project_id').value;
            const projectSelect = document.getElementById('project_id');


            if (!project_id) {
                event.preventDefault();
                projectSelect.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'กรุณาเลือกโครงการ',
                });
            } else {

                projectSelect.classList.remove('is-invalid');
            }

        });


    </script>


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

            });


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
                            //console.log(data.plans);
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


        });
    </script>
@endpush
