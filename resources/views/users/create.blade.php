@extends('_layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')

<div class="container-fluid">

    <div class="card shadow mb-4">

        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">
                Tambah Pengguna
            </h6>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="card-body">

                {{-- Error Validasi --}}
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text"
                                name="username"
                                value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror">
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>NRK</label>
                            <input type="text"
                                name="nrk"
                                value="{{ old('nrk') }}"
                                class="form-control @error('nrk') is-invalid @enderror">
                            @error('nrk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select
                                name="role"
                                class="form-control @error('role') is-invalid @enderror">
                                <option value="">Pilih Role</option>
                                <option value="admin helpdesk">Admin Helpdesk</option>
                                <option value="petugas teknis">Petugas Teknis</option>
                                <option value="pengguna">Pengguna</option>
                            </select>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password"
                                name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer text-right">

                <a href="{{ route('manage.user-roles.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <button class="btn btn-primary">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection