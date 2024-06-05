@extends('layouts.app')

@section('title', 'รีพอร์ต')

@section('content')
    <style>
        tr th {
            font-size: 12px;
            text-align: center;
        }

        tbody td {
            font-size: 12px;
            white-space: nowrap;
        }
    </style>


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">รีพอร์ต </h1>

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
                        <div class="col-md-4 col-6">
                            <!-- small box -->
                            <a href="{{ route('report.search.in') }}" class="small-box-footer">
                                <div class="small-box bg-gradient-secondary">
                                    <div class="inner">
                                        <h3></h3>

                                        <p>IN</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-building"></i>
                                    </div>

                                </div>
                            </a>
                        </div>

                        <div class="col-md-4 col-6">
                            <!-- small box -->
                            <a href="{{ route('report.search.out') }}" class="small-box-footer">
                                <div class="small-box bg-gradient-secondary">
                                    <div class="inner">
                                        <h3></h3>

                                        <p>OUT</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-4 col-6">
                            <!-- small box -->
                            <a href="{{ route('report') }}" class="small-box-footer">
                                <div class="small-box bg-gradient-success">
                                    <div class="inner">
                                        <h3></h3>

                                        <p>ALL</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-ban"></i>
                                    </div>

                                </div>
                            </a>
                        </div>
                        <!-- ./col -->

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">ค้นหา</h3>

                        </div>
                        <form action="{{ route('report.search') }}" method="POST" id="searchForm">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row justify-content-center">

                                    <label for="" class="col-sm-1 col-form-label text-right ">วันที่เริ่มต้น</label>
                                    <div class="col-sm-2 my-auto">
                                        <input class="form-control datepicker" name="start_date" id="start_date"
                                            type="text" value="" placeholder="วันที่เริ่มต้น" autocomplete="off">
                                    </div>

                                    <label for="" class="col-form-label text-right ">ถึง</label>
                                    <div class="col-sm-2 my-auto">
                                        <input class="form-control datepicker" name="end_date" id="end_date" type="text"
                                            value="" placeholder="วันที่สิ้นสุด" autocomplete="off">
                                    </div>

                                    <label for="" class="col-sm-1 col-form-label text-right">ประเภท</label>
                                    <select name="type" id="type" class="col-sm-1 form-control" autocomplete="off">
                                        <option value="all">ทั้งหมด</option>
                                        <option value="in">in</option>
                                        <option value="out">out</option>
                                    </select>

                                    <label for="project_id" class="col-sm-1 col-form-label text-right">โครงการ</label>
                                    <select name="project_id" id="project_id" class="col-sm-2 form-control">
                                        <option value="0">เลือก</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>


                                </div>
                                <div class="box-footer text-center">
                                    <button type="submit" class="btn btn-success">ค้นหา</button>
                                    <a href="{{ route('report') }}" type="button" class="btn btn-danger">เคลียร์</a>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!--Table-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">รีพอร์ตทั้งหมด <button id="export-btn"
                                    class="btn btn-sm bg-gradient-primary"><i class="fa fa-file-excel"></i>
                                    Export Excel </button></h3>

                        </div>

                        <div class="card-body">

                            <table id="my-table" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width:30% ; vertical-align: middle;">โครงการ</th>
                                        <th style="vertical-align: middle;">ห้องทั้งหมด</th>
                                        <th style="vertical-align: middle;">โอนแล้ว</th>
                                        <th style="vertical-align: middle;">จองแล้ว</th>
                                        <th style="vertical-align: middle;">ทำสัญญา</th>
                                        <th style="vertical-align: middle;">ห้องเหลือ</th>
                                        <th style="vertical-align: middle;">จองทำสัญญา</th>
                                        <th style="vertical-align: middle;">ไม่พร้อมขาย</th>
                                        <th style="vertical-align: middle;">เกินSLA</th>
                                        <th style="vertical-align: middle;">%</th>
                                        <th style="vertical-align: middle;">SLA จอง</th>
                                        <th style="vertical-align: middle;">SLA ทำสัญญา</th>
                                        <th style="vertical-align: middle;">เงินมัดจำ</th>
                                        <th style="vertical-align: middle;">เงินจองแล้ว</th>
                                        <th style="vertical-align: middle;">เงินทำสัญญา</th>
                                        <th style="vertical-align: middle;">เงินทั้งหมด</th>
                                        <th style="vertical-align: middle;">วันเริ่ม สัญญา</th>
                                        <th style="vertical-align: middle;">วันสิ้นสุด สัญญา</th>
                                    </tr>

                                </thead>
                                <tbody>


                                    @php
                                        $sumAllroom = 0;
                                        $sumMortgage = 0;
                                        $sumBooking = 0;
                                        $sumContract = 0;
                                        $sumAvailable = 0;
                                        $sumNotready = 0;
                                        $sumSlabooking = 0;
                                        $sumSlacontract = 0;
                                        $sumDeposit = 0;
                                        $sumDivSla = 0;
                                        $sumBookingprice = 0;
                                        $sumContractprice = 0;
                                        $sumPriceAll = 0;

                                    @endphp

                                    @foreach ($data as $item)
                                        @php
                                            if ($item['project']->booking + $item['project']->contract == 0) {
                                                $divSla = $item['project']->slabooking + $item['project']->slacontract;
                                            } else {
                                                $divSla = ($item['project']->slabooking + $item['project']->slacontract) / ($item['project']->booking + $item['project']->contract);
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $item['project']->name }}</td>
                                            <td class="text-right">{{ number_format($item['project']->all_rooms, 0) }}</td>
                                            <td class="text-right">{{ number_format($item['project']->mortgage, 0) }}</td>
                                            <td class="text-right">{{ number_format($item['project']->booking, 0) }}</td>
                                            <td class="text-right">{{ number_format($item['project']->contract, 0) }}</td>
                                            <td class="text-right">{{ number_format($item['project']->available, 0) }}</td>
                                            <td class="text-right">
                                                {{ number_format($item['project']->booking + $item['project']->contract, 0) }}
                                            </td>
                                            <td class="text-right"> {{number_format($item['project']->notready, 0)}} </td>
                                            <td class="text-right">
                                                {{ number_format($item['project']->slabooking + $item['project']->slacontract, 0) }}
                                            </td>
                                            <td class="text-right">{{ number_format($divSla * 100, 0) }}%</td>
                                            <td class="text-right">{{ number_format($item['project']->slabooking, 0) }}
                                            </td>
                                            <td class="text-right">{{ number_format($item['project']->slacontract, 0) }}
                                            </td>
                                            <td class="text-right">{{ number_format($item['project']->deposit, 0) }}</td>
                                            <td class="text-right">{{ number_format($item['project']->booking_price, 0) }}
                                            </td>
                                            <td class="text-right">{{ number_format($item['project']->contract_price, 0) }}
                                            </td>
                                            <td class="text-right">{{ number_format($item['price_all'], 0) }}</td>
                                            <td class="text-right">{{ $item['project']->start_date }}</td>
                                            <td class="text-right">{{ $item['project']->end_date }}</td>
                                        </tr>
                                        @php
                                            $sumAllroom += $item['project']->all_rooms;
                                            $sumMortgage += $item['project']->mortgage;
                                            $sumBooking += $item['project']->booking;
                                            $sumContract += $item['project']->contract;
                                            $sumAvailable += $item['project']->available;
                                            $sumNotready += $item['project']->notready;
                                            $sumSlabooking += $item['project']->slabooking;
                                            $sumSlacontract += $item['project']->slacontract;
                                            $sumDivSla += $item['project']->contract + $item['project']->booking;
                                            $sumDeposit += $item['project']->deposit;
                                            $sumBookingprice += $item['project']->booking_price;
                                            $sumContractprice += $item['project']->contract_price;
                                            $sumPriceAll += $item['price_all'];
                                        @endphp
                                    @endforeach

                                    <tr class="text-right">
                                        <td colspan="2">รวม</td>
                                        <td>{{ number_format($sumAllroom, 0) }}</td>
                                        <td>{{ number_format($sumMortgage, 0) }}</td>
                                        <td>{{ number_format($sumBooking, 0) }}</td>
                                        <td>{{ number_format($sumContract, 0) }}</td>
                                        <td>{{ number_format($sumAvailable, 0) }}</td>
                                        <td>{{ number_format($sumBooking + $sumContract, 0) }}</td>
                                        <td>{{ number_format($sumNotready, 0) }}</td>
                                        <td>{{ number_format($sumSlabooking + $sumSlacontract, 0) }}</td>
                                        @if ($sumDivSla !== 0)
                                            <td>{{ number_format((($sumSlabooking + $sumSlacontract) / $sumDivSla) * 100, 0) }}%
                                            </td>
                                        @else
                                            <td>0%</td>
                                        @endif
                                        <td>{{ number_format($sumSlabooking, 0) }}</td>
                                        <td>{{ number_format($sumSlacontract, 0) }}</td>
                                        <td>{{ number_format($sumDeposit, 0) }}</td>
                                        <td>{{ number_format($sumBookingprice, 0) }}</td>
                                        <td>{{ number_format($sumContractprice, 0) }}</td>
                                        <td>{{ number_format($sumPriceAll, 0) }}</td>
                                        <td colspan="2"></td>

                                    </tr>

                                </tbody>


                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">สรุปรายละเอียดห้องโครงการ</h3>

                        </div>

                        <div class="card-body">

                            <table id="" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <th colspan="3">IN</th>
                                    <th colspan="3">OUT</th>
                                    <th colspan="3">รวม</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left" colspan="2">โอนแล้ว</td>
                                        <td class="text-right">{{ number_format($pro_in_mortgage, 0) }}</td>
                                        <td class="text-left" colspan="2">โอนแล้ว</td>
                                        <td class="text-right">{{ number_format($pro_out_mortgage, 0) }}</td>
                                        <td class="text-left" colspan="2">โอนแล้ว</td>
                                        <td class="text-right">{{ number_format($pro_in_mortgage + $pro_out_mortgage, 0) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-left" colspan="2">ออกสัญญา</td>

                                        <td class="text-right">{{ number_format($pro_in_contract, 0) }}</td>
                                        <td class="text-left" colspan="2">ออกสัญญา</td>

                                        <td class="text-right">{{ number_format($pro_out_contract, 0) }}</td>
                                        <td class="text-left" colspan="2">ออกสัญญา</td>

                                        <td class="text-right">{{ number_format($pro_in_contract + $pro_out_contract, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left">จอง</td>
                                        <td class="text-right">{{ number_format($pro_in_waiting, 0) }}</td>
                                        <td colspan="2" class="text-left">จอง</td>
                                        <td class="text-right">{{ number_format($pro_out_waiting, 0) }}</td>
                                        <td colspan="2" class="text-left">จอง</td>
                                        <td class="text-right">{{ number_format($pro_in_waiting + $pro_out_waiting, 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left">ห้องว่าง</td>
                                        <td class="text-right">{{ number_format($pro_in_available, 0) }}</td>
                                        <td colspan="2" class="text-left">ห้องว่าง</td>
                                        <td class="text-right">{{ number_format($pro_out_available, 0) }}</td>
                                        <td colspan="2" class="text-left">ห้องว่าง</td>
                                        <td class="text-right">
                                            {{ number_format($pro_in_available + $pro_out_available, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left">ไม่พร้อมขาย</td>
                                        <td class="text-right">{{ number_format($pro_in_notready, 0) }}</td>
                                        <td colspan="2" class="text-left">ไม่พร้อมขาย</td>
                                        <td class="text-right">{{ number_format($pro_out_notready, 0) }}</td>
                                        <td colspan="2" class="text-left">ไม่พร้อมขาย</td>
                                        <td class="text-right">
                                            {{ number_format($pro_in_notready + $pro_out_notready, 0) }}</td>
                                    </tr>



                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>









        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.8/xlsx.full.min.js"></script>
    <!-- Return Form-->
    @if (isset($formInputs))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var formInputs = @json($formInputs);

                Object.keys(formInputs).forEach(function(key) {
                    var input = document.querySelector('[name="' + key + '"]');
                    if (input) {
                        input.value = formInputs[key];
                    }
                });
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // รูปแบบวันที่
                autoclose: true,
            });

            $('#start_date').on('changeDate', function(e) {
                var selectedStartDate = e.date;
                $('#end_date').datepicker('setStartDate', selectedStartDate);
            });

        });
    </script>
    <script>
        // Add event listener to the export button
        document.getElementById('export-btn').addEventListener('click', exportTableToExcel);

        // Function to export the HTML table to an Excel file
        function exportTableToExcel() {
            // Get the HTML table element
            const table = document.getElementById('my-table');

            // Convert the table to a worksheet
            const worksheet = XLSX.utils.table_to_sheet(table);

            const workbook = XLSX.utils.book_new();

            // Add the worksheet to the workbook
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            // Export the workbook to an Excel file
            XLSX.writeFile(workbook, 'report-all.xlsx');
        }
    </script>
@endpush
