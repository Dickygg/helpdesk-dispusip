@extends('_layouts.app')

@section('title', 'Tambah Permission')
@section('page-title', 'Tambah Permission')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Permission</h6>
        <a href="{{ route('manage.permissions.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('manage.permissions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="font-weight-bold">
                    Nama Permission <span class="text-danger">*</span>
                </label>
                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="contoh: laporan.view">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Gunakan format <code>resource.aksi</code> —
                    contoh: <code>tiket.view</code>, <code>laporan.export</code>
                </small>
            </div>
            <hr>
            <a href="{{ route('manage.permissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection