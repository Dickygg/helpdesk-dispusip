@push('style')
<style>
    .status-rejected {
        background: #f66d6dff;
        color: #f8f6f6ff;
    }
</style>
@endpush
@php
$prefix = match(true) {
auth()->user()->hasRole('super admin') => 'sa.',
auth()->user()->hasRole('admin helpdesk') => 'admin.',
default => ''
};
@endphp
<div class="d-flex justify-content-center align-content-center" style="width: 100%;">
    <div class="card">
        <div class="row">
            <div class=" col-12">
                <div class="card-body d-flex flex-column justify-content-center ">
                    <h5 class=" card-title d-flex justify-content-center">Data Tiket Tidak Ditemuka!.</h5>
                    <p class="card-text">Anda belum memiliki tiket aktif saat ini. Buat tiket baru untuk melaporkan kendala Anda.</p>
                    <a href="{{route($prefix.'tiket.create')}}" class="btn btn-outline-primary">Buat Tiket</a>
                </div>
            </div>
        </div>
    </div>
</div>