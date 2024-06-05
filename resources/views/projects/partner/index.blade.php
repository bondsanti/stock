@extends('layouts.app')



@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">


                    <h1 class="m-0">โครงการ



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

            <!--Table-->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-outline card-lightblue">
                            <h3 class="card-title">โครงการ ทั้งหมด <b>{{$count_isActive}}</b></h3>

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
                                                    <span class="badge bg-success">Active</span>
                                                    @elseif($item->active == 2)
                                                    <span class="badge bg-secondary">Hidden</span>
                                                    @else
                                                    <span class="badge bg-danger">UnActive</span>
                                                    @endif

                                                </small>
                                            </td>


                                            <td width="15%" class="text-center">
                                                <a href="{{ url('/projects/detail/' . $item->id) }}"
                                                    class="btn bg-gradient-info btn-sm edit-item" data-toggle="tooltip" data-placement="top" title="ดูรายละเอียด">
                                                    <i class="fa fa-th-list">
                                                    </i>

                                                </a>
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


        });
    </script>
@endpush
