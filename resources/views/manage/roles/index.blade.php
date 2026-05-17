@extends('_layouts.app')

@section('title', 'Manage Roles')
@section('page-title', 'Manage Roles')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Role</h6>
        <a href="{{ route('manage.roles.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah Role
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Role</th>
                        <th>Permissions</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $i => $role)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @forelse($role->permissions as $perm)
                            <span class="badge badge-secondary">{{ $perm->name }}</span>
                            @empty
                            <span class="text-muted small font-italic">Belum ada permission</span>
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('manage.roles.edit', $role) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!in_array($role->name, ['Super Admin', 'Admin Helpdesk', 'Petugas Teknis', 'Pengguna']))
                            <form action="{{ route('manage.roles.destroy', $role) }}"
                                method="POST" class="d-inline"
                                id="delete-role-{{ $role->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('delete-role-{{ $role->id }}', '{{ $role->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(formId, roleName) {
        Swal.fire({
            title: 'Hapus Role?',
            text: `Role "${roleName}" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush