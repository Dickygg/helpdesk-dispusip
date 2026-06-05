@extends('_layouts.app')

@section('title','Manajemen Pengguna')
@section('page-title','Manajemen Pengguna')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">

            <h6 class="m-0 font-weight-bold text-primary">
                Data Pengguna
            </h6>

            <a href="{{route('users.create')}}"
                class="btn btn-primary btn-sm">

                Tambah Pengguna

            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NRK</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->nrk }}</td>

                        <td>{{ $user->username }}</td>

                        <td>{{ $user->email }}</td>

                        <td>
                            {{ $user->roles->pluck('name')->implode(', ') }}
                        </td>

                        <td>

                            <a href="{{route('users.edit',$user->id)}}"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form
                                action="#"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Hapus data?')"
                                    class="btn btn-danger btn-sm">

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="7" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $users->links() }}

        </div>

    </div>

</div>

@endsection