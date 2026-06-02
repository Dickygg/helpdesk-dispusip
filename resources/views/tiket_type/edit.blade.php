@extends('_layouts.app')
@section('title', 'Ubah Data Tipe Tiket')
@section('page-title', 'Ubah Data Tipe Tiket')

@section('content')
<div class="content">
    <div class="main">
        <form action="{{ route('ticket-type.update',$data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        {{-- Kolom kiri --}}
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Tiket Tipe<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $data->name }}">
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') ?? $data->description }}">
                                @error('description')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                                <a href="{{ route(substr(\Request::route()->getName(), 0, strripos(\Request::route()->getName(), '.')) . '.index') }}"
                                    class="btn btn-outline-danger me-auto text-nowrap">
                                    <i class="fa-solid fa-arrow-left-long"></i>
                                    <span class="d-md-inline-block ms-1">Kembali</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
@endpush