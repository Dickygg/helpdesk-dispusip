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

<body class="bg-gradient-white">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-5">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="{{ asset('sb-admin/img/Logo-DispusipHelpdesk.png') }}" alt="logo" class="ml-lg-2">
                                        <h5 class="fw-bolder mt-3 mb-3" style="color: #0e4a65; font-weight:700;">LOGIN</h5>
                                    </div>

                                    {{-- Form action diubah ke route('login') --}}
                                    <form class="user" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        {{-- Username field --}}
                                        <div class="form-group">
                                            <input
                                                type="text"
                                                class="form-control form-control-user @error('username') is-invalid @enderror"
                                                style="border-radius: 0.35rem !important;"
                                                id="username"
                                                name="username"
                                                value="{{ old('username') }}"
                                                placeholder="NRK/Username"
                                                required
                                                autofocus
                                                autocomplete="username">
                                            {{-- Tampilkan error username --}}
                                            @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Password field --}}
                                        <div class="form-group">
                                            <input
                                                type="password"
                                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                                style="border-radius: 0.35rem !important;"
                                                id="password"
                                                name="password"
                                                placeholder="Password"
                                                required
                                                autocomplete="current-password">
                                            {{-- Tampilkan error password --}}
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Remember Me --}}
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                                <label class="custom-control-label" for="remember">Ingat Saya</label>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-md btn-dispusip w-100">Login</button>
                                            </div>
                                        </div>
                                        <hr>
                                    </form>
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