@extends('_layouts.app')
@section('title', 'Profile')
@section('page-title', 'Profile')
@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-md-8">

            {{-- Edit Profile --}}
            <div class="card shadow mb-4">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Edit Profile
                    </h6>
                </div>

                <div class="card-body">

                    <form action="{{ route('profile.update') }}"
                        method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Nama</label>
                                <input type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', auth()->user()->name) }}">

                                @error('name')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>NRK</label>
                                <input type="text"
                                    name="nrk"
                                    class="form-control @error('nrk') is-invalid @enderror"
                                    value="{{ old('nrk', auth()->user()->nrk) }}">

                                @error('nrk')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Username</label>
                                <input type="text"
                                    name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', auth()->user()->username) }}">

                                @error('username')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', auth()->user()->email) }}">

                                @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>

                    </form>

                </div>

            </div>

            {{-- Ubah Password --}}
            {{-- Ubah Password --}}
            <div class="card shadow">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Ubah Password
                    </h6>
                </div>

                <div class="card-body">

                    <form action="{{ route('profile.update-password') }}"
                        method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Password Lama</label>
                            <input type="password"
                                name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror">

                            @error('current_password')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Password Baru</label>
                            <input type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror">

                            @error('password')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Konfirmasi Password</label>
                            <input type="password"
                                name="password_confirmation"
                                class="form-control">
                        </div>

                        <button type="submit"
                            class="btn btn-success">
                            Ubah Password
                        </button>

                    </form>

                </div>

            </div>

        </div>
        <div class="col-md-4">

            <div class="card shadow mb-4">

                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Informasi Akun
                    </h6>
                </div>

                <div class="card-body">

                    <table class="table table-borderless">

                        <tr>
                            <th width="40%">Nama</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>

                        <tr>
                            <th>NRK</th>
                            <td>{{ auth()->user()->nrk }}</td>
                        </tr>

                        <tr>
                            <th>Username</th>
                            <td>{{ auth()->user()->username }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>

                        <tr>
                            <th>Bergabung</th>
                            <td>
                                {{ auth()->user()->created_at->format('d M Y') }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection