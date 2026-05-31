<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - {{config('app.name')}}</title>

    <link href="{{ asset('sb-admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('sb-admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('sb-admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<style>
    .login-banner {
        min-height: 700px;

        background-image:url('{{ asset("sb-admin/img/imageLogin.png") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

<body class="bg-gradient-white">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card o-hidden border-0 shadow-lg my-5" style="height: 75%;">
                    <div class="row no-gutters">
                        <!-- LEFT SIDE -->
                        <div class="col-lg-6 d-none d-lg-block p-0 login-banner"></div>
                        <!-- RIGHT SIDE -->
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <img src="{{ asset('sb-admin/img/Logo-DispusipHelpdesk.png') }}"
                                        width="80" class="d-lg-none">
                                    <h3 class="mt-4 font-weight-bold"
                                        style="color:#0e4a65;">
                                        Selamat Datang
                                    </h3>
                                    <p class="text-muted">
                                        Silakan login untuk melanjutkan
                                    </p>
                                </div>
                                <form class="user"
                                    method="POST"
                                    action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label>NRK / Username</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-person"></i>
                                                </span>
                                            </div>

                                            <input
                                                type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username"
                                                value="{{ old('username') }}"
                                                placeholder="Masukkan Username"
                                                required
                                                autofocus>

                                            @error('username')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                            </div>

                                            <input
                                                type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password"
                                                placeholder="Masukkan Password"
                                                required>

                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit"
                                        class="btn btn-block text-white"
                                        style="
                                background:#0e4a65;
                                height:50px;
                                border-radius:12px;
                                font-weight:600;">

                                        <i class="bi bi-box-arrow-in-right mr-2"></i>
                                        Login

                                    </button>

                                </form>

                                <div class="text-center mt-5 text-muted">
                                    © {{ date('Y') }} Dispusip Helpdesk
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('sb-admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sb-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sb-admin/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert session success --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
        });
    </script>
    @endif

    {{-- SweetAlert session error --}}
    @if(session('error'))
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