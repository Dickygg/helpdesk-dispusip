<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login - {{config('app.name')}}</title>

    <!-- Custom fonts for this template-->
    <!-- Custom fonts for this template-->
    <link href="{{ asset('sb-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('sb-admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('sb-admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        .poppins {
            font-family: poppins;
        }
    </style>

</head>

<body class="bg-gradient-white">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center mt-5">

            <div class="col-lg-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="{{ asset('sb-admin/img/Logo-DispusipHelpdesk.png')}}" alt="gagal" class="ml-lg-2">
                                        <h5 class="fw-bolder mt-3 mb-3" style="color:  #0e4a65; font-weight:700;">LOGIN</h5>
                                    </div>

                                    <form class="user" method="POST" action="{{ route('testlayout') }}">
                                        @csrf
                                        <div class="form-group">

                                            <input type="email" class="form-control form-control-user" style="border-radius: 0.35rem !important;"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="NRK/Username" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">

                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" style="border-radius: 0.35rem !important;"
                                                placeholder="Password" id="password"
                                                type="password"
                                                name="password"
                                                required autocomplete="current-password">
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-md btn-dispusip w-100">Login</button>
                                            </div>
                                        </div>
                                        <hr>
                                    </form>
                                    <!-- <div class="text-center">
                                        @if (Route::has('password.request'))
                                        <a class="small underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                            {{ __('Lupa Password?') }}
                                        </a>
                                        @endif
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <!-- 1. jQuery HARUS PERTAMA -->
    <script src="{{ asset('sb-admin/vendor/jquery/jquery.min.js') }}"></script>

    <!-- 2. Bootstrap Bundle -->
    <script src="{{ asset('sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- 3. Core plugin JavaScript-->
    <script src="{{ asset('sb-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- 4. DataTables -->
    <script src="{{ asset('sb-admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- 5. Custom scripts for all pages-->
    <script src="{{ asset('sb-admin/js/sb-admin-2.min.js') }}"></script>

    <!-- 6. SweetAlert2 (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 7. DataTable Initialization -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "pageLength": 10,
                "ordering": true,
                "searching": true,
                "info": true,
                "lengthChange": true,
                "responsive": true
            });
        });
    </script>
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
        });
    </script>
    @endif

    @stack('scripts')

</body>

</html>