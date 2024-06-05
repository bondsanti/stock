@extends('layouts.app')

@section('title', 'แดชบอร์ด')

@section('content')
    {{-- @php
    use Carbon\Carbon;
@endphp --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">แดชบอร์ด</h1>
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


                    <div class="row">

                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($all) }}</h3>

                                        <p>ห้องทั้งหมด</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-building"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($empty) }}</h3>

                                        <p>ห้องว่าง</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-home"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($bid) }}</h3>

                                        <p>ออกใบเสนอราคา</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-file-text"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($bookings) }}</h3>

                                        <p>จองแล้ว</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-calendar-check"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3>{{ number_format($contract) }}</h3>

                                        <p>ห้องทำสัญญา</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-2 col-6">
                            <!-- small box -->
                            <a href="#" class="small-box-footer">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ number_format($mortgage) }}</h3>

                                        <p>ห้องโอน</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check-circle"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
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
        });
    </script>
@endpush
