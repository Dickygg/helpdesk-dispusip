@extends('_layouts.app')

@section('title', 'Tambah Role')
@section('page-title', 'Tambah Role')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Role</h6>
        <a href="{{ route('manage.roles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('manage.roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="font-weight-bold">
                    Nama Role <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="contoh: Supervisor">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Permissions</label>
                @php
                $grouped = $permissions->groupBy(fn($p) => explode('.', $p->name)[0]);
                @endphp
                <div class="row mt-2">
                    @foreach($grouped as $group => $perms)
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-primary shadow-sm">
                            <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold text-capitalize text-primary">
                                    {{ $group }}
                                </span>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="custom-control-input check-all"
                                        id="all_{{ $group }}"
                                        data-group="{{ $group }}">
                                    <label class="custom-control-label small" for="all_{{ $group }}">
                                        Semua
                                    </label>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                @foreach($perms as $perm)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="custom-control-input perm-check-{{ $group }}"
                                        name="permissions[]"
                                        value="{{ $perm->name }}"
                                        id="perm_{{ $perm->id }}"
                                        {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label small" for="perm_{{ $perm->id }}">
                                        {{ $perm->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <hr>
            <a href="{{ route('manage.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.check-all').forEach(function(checkAll) {
        checkAll.addEventListener('change', function() {
            const group = this.dataset.group;
            document.querySelectorAll(`.perm-check-${group}`).forEach(function(cb) {
                cb.checked = checkAll.checked;
            });
        });
    });
</script>
@endpush