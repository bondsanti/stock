<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>vBis | Stock</title>
    <link rel="icon" type="image/x-icon" href="{{ url('uploads/vbeicon.ico') }}">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('vendors/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/dist/css/adminlte.min.css?v=3.2.0') }}">
    <link rel="stylesheet" href="{{ asset('vendors/custom.css') }}">
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;600&family=Prompt:wght@300;600;&display=swap" rel="stylesheet"> --}}



</head>

<body class="hold-transition login-page">
    @include('sweetalert::alert')
    <br><br><br><br>
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ url('uploads/logovb2.png') }}">
        </div>
        <div class="alert alert-warning text-center" role="alert">
            <strong>รหัสผ่านควรประกอบด้วย</strong> ตัวเล็ก/ใหญ่, เลข, และอักษรพิเศษ อย่างละ 1 ตัว และไม่ต่ำกว่า 8 ตัวอักษร
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <h2 class="text-center mb-2">Change Password</h2>
                <form action="{{route('update.password')}}" id="passwordForm" method="post">
                    @csrf
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">

                        <div class="input-group-append">
                            <div class="input-group-text text-left">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                    </div>
                    <small class="text-danger">@error('password'){{$message}} @enderror</small>
                    <div class="input-group mt-3">
                        <input type="password" class="form-control" name="cfpassword" placeholder="Confirm Password"
                            autocomplete="off">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                    </div>
                    <small class="text-danger">@error('cfpassword'){{$message}} @enderror</small>
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-block bg-gradient-info">
                                ยันยัน</button>
                        </div>

                    </div>
                </form>



            </div>

        </div>
    </div>


    <script src="{{ asset('vendors/plugins/jquery/jquery.min.js') }}"></script>

    <script src="{{ asset('vendors/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('vendors/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendors/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('vendors/dist/js/adminlte.min.js?v=3.2.0') }}"></script>

    <script>

        $(function() {
            $('#passwordForm').validate({
                rules: {
                    password: {
                        required: true,
                    },
                    cfpassword: {
                        required: true,
                    },
                },
                messages: {
                    password: {
                        required: "ป้อนรหัสผ่าน",
                    },
                    cfpassword: {
                        required: "ป้อนยืนยันรหัสผ่าน",
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

        });
    </script>
</body>

</html>
