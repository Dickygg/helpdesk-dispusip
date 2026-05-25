@extends('_layouts.app')
@section('title', 'Buat Tiket')
@section('page-title', 'Buat Tiket')
@section('content')
@push('styles')
@endpush

@php
$prefix = match(true) {
auth()->user()->hasRole('super admin') => 'sa.',
auth()->user()->hasRole('admin helpdesk') => 'admin.',
default => ''
};
@endphp
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary ">Dispusip<span class="text-info">Helpdesk.</span></h6>
        </div>
        <div class="card-body">
            <div class="card shadow-sm">
                <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <div class="card-body">
                        <div class="row">
                            {{-- Kolom kiri --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Judul Tiket<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? '' }}">
                                    @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Aplikasi Yang Digunakan<span class="text-danger">*</span></label>
                                        <select class="form-select form-control" aria-label="Default select example" name="id_aplikasi" id="id_aplikasi">
                                            <option selected>----</option>
                                            @foreach($aplikasi as $s)
                                            <option value="{{$s->id}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('id_aplikasi')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi Keluhan<span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        name="description"
                                        rows="4">{{ old('description') ?? '' }}</textarea>
                                    @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tangkapan Layar<span class="text-danger">*</span></label>
                                    <div class="col-md-9">
                                        <label class="custom-file-upload w-100">
                                            <input name="file" type="file"
                                                accept="{{ config('upload.file.accept_html.image') }}"
                                                data-accept_name="{{ config('upload.file.accept_name.image') }}"
                                                data-max_size="{{ config('upload.file.max.image') }}"
                                                id="fileInput">
                                            <div class="file-upload-box">
                                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                                <span id="fileLabel">Pilih File...</span>
                                            </div>
                                        </label>
                                        <small class="text-muted">
                                            Format: {{ config('upload.file.accept_name.image') }} |
                                            Maks: {{ config('upload.file.max.image') }} KB
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered m-0 mx-auto" role="document"
        style="width: fit-content; max-width: 95vw;">
        <div class="modal-content border-0 bg-transparent shadow-none p-0">
            <img src="{{ asset('storage/alur_tiket.png') }}"
                alt="poster"
                style="width: 70vw; max-width: 500px; max-height: 80vh; object-fit: contain; border-radius: 8px; display: block;">
        </div>
    </div>
</div>




@push('scripts')
<!-- Trigger otomatis saat halaman dibuka -->
<script>
    $(document).ready(function() {
        $('#infoModal').modal('show');
    });
</script>

@if ($errors->any())
<script>
    $(document).ready(function() {
        $('#statusbaruModal').modal('show');
    });
</script>
@endif
@endpush
@endsection