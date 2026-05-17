@extends('_layouts.app')

@section('title', 'Manage Permissions')
@section('page-title', 'Manage Permissions')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Permission</h6>
        <a href="{{ route('manage.permissions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus fa-sm"></i> Tambah Permission
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Permission</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $i => $perm)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $perm->name }}</td>
                        <td>
                            <form action="{{ route('manage.permissions.destroy', $perm) }}"
                                method="POST" class="d-inline"
                                id="delete-perm-{{ $perm->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('delete-perm-{{ $perm->id }}', '{{ $perm->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
    function confirmDelete(formId, permName) {
        Swal.fire({
            title: 'Hapus Permission?',
            text: `Permission "${permName}" akan dihapus. Role yang memiliki permission ini akan terpengaruh.`,
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