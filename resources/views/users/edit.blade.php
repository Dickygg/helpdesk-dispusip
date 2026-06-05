@extends('_layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')

<div class="container-fluid">

    <div class="card shadow mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Dispusip<span class="text-info">Helpdesk.</span>
            </h6>
        </div>

        <div class="card-body">

            <div class="card shadow-sm">

                <form action="{{ route('users.update', $user->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        <div class="row">

                            {{-- Kolom Kiri --}}
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label">
                                        Nama Lengkap
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        class="form-control @error('name') is-invalid @enderror">

                                    @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Username
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="username"
                                        value="{{ old('username', $user->username) }}"
                                        class="form-control @error('username') is-invalid @enderror">

                                    @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        NRK
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="nrk"
                                        value="{{ old('nrk', $user->nrk) }}"
                                        class="form-control @error('nrk') is-invalid @enderror">

                                    @error('nrk')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label">
                                        Email
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        class="form-control @error('email') is-invalid @enderror">

                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Role
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select
                                        name="role"
                                        class="form-control @error('role') is-invalid @enderror">

                                        <option value="">
                                            Pilih Role
                                        </option>

                                        @foreach($roles as $role)
                                        <option
                                            value="{{ $role }}"
                                            {{ $user->hasRole($role) ? 'selected' : '' }}>
                                            {{ ucwords($role) }}
                                        </option>
                                        @endforeach

                                    </select>

                                    @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <hr>

                                <h6 class="font-weight-bold text-primary">
                                    Ganti Password (Opsional)
                                </h6>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Password Baru
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control">

                                    <small class="text-muted">
                                        Kosongkan jika tidak ingin mengubah password.
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Konfirmasi Password Baru
                                    </label>

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control">
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer text-end">

                        <a href="{{ route('users.index') }}"
                            class="btn btn-secondary">

                            Kembali

                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Update Data

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection