@extends('_layouts.app')

@section('title', 'Assign Role User')
@section('page-title', 'Assign Role User')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar User & Role</h6>
        <a href="{{route('users.create')}}"
            class="btn btn-primary btn-sm">
            Tambah Pengguna
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $i => $user)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            @forelse($user->roles as $role)
                            <span class="badge badge-primary">{{ $role->name }}</span>
                            @empty
                            <span class="badge badge-warning text-dark">Belum ada role</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('manage.user-roles.edit', $user) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-user-tag"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection