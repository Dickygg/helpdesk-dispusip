@extends('_layouts.app')
@section('title', 'Daftar Tiket')
@section('page-title', 'Daftar Tiket')
@section('content')
@push('styles')
<style>
    .icon-ticket {
        margin-right: 8px;
        font-size: 1rem;
        width: 32px;
        height: 32px;
        padding: 0;
        color: #3b82f6;
        background-color: #d3e5fdff;
        border-radius: 35%;
    }

    .colums-card-body {
        border-right: 1px solid #dee2e6;
    }

    .title-cardtiket {
        font-size: 0.65rem;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 0;

    }

    .total-card {
        font-size: 0.95rem;
        font-weight: bold;
        margin-top: 0;
    }

    .icon-alur {
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 35px;
        height: 35px;
        margin-right: 8px;
    }

    .icon-text {
        display: flex;
        flex-direction: column;
        justify-content: start;
        align-items: flex-start;
        white-space: nowrap;
    }

    .colums-card-body {
        border-right: 2px solid #dee2e6;
    }

    .card-costum {
        background-color: #cffcd847;
    }

    @media (max-width:767.98px) {
        .colums-card-body {
            border-right: 0px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 10px;
            margin-top: 5px;
        }
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
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-costum p-3">
                <div class="mb-3">
                    <div class="fw-bold text-primary" style="font-size: 0.85rem; font-weight: bold;">Alur Sistem Tiket</div>
                </div> <!-- Flow Status -->
                <div class="container-fluid">
                    <div class="row g-3 text-center mb-3">
                        <!-- Open -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 icon-alur"> <i class="bi bi-folder-fill text-white mt-1"></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Open</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Pengguna membuat tiket</div>
                                </div>
                            </div>
                        </div>
                        <!-- Accept -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 icon-alur"> <i class="bi bi-check-circle-fill text-white mt-1"></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Accept</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Tiket Sudah Diverifikasi</div>
                                </div>
                            </div>
                        </div>
                        <!-- Assigned -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 icon-alur"> <i class="bi bi-person-fill-check text-white"></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Assigned</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Petugas Sudah Dipilih</div>
                                </div>
                            </div>
                        </div>
                        <!-- In Progress -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle icon-alur"> <i class="bi bi-arrow-repeat text-white mt-1"></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Progress</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Tiket Sedang diproses</div>
                                </div>
                            </div>
                        </div>
                        <!-- Resolved -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 icon-alur"> <i class="bi bi-check-circle-fill text-white mt-1"></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Resolved</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Menunggu User Konfirmasi</div>
                                </div>
                            </div>
                        </div>
                        <!-- Closed -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center colums-card-body">
                                <div class="bg-dark bg-opacity-10 icon-alur"> <i class="bi bi-lock-fill text-white"></i></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Closed</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Tiket Selesai</div>
                                </div>
                            </div>
                        </div>
                        <!-- Rejected -->
                        <div class="col-lg col-md-4 col-sm-6 mb-3 mb-lg-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 icon-alur"> <i class="bi bi-x-circle-fill text-white mt-1"></i> </div>
                                <div class="icon-text">
                                    <div class="text-primary" style="font-size:0.8rem; font-weight:bold;">Rejected</div>
                                    <div class="text-secondary" style="font-size:0.6rem; font-weight:bold;">Data Ditolak</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary ">Dispusip<span class="text-info">Helpdesk.</span></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-9 d-none d-md-flex">
                    <div class="btn-group" role="btn-group">
                        <a href="{{ route($prefix . 'tiket.history', array_merge(request()->query(), ['status' => ''])) }}">
                            <button type="button" class="btn btn-sm px-4 {{ !request('status') ? 'btn-primary' : 'btn-white border' }}">
                                <i class="bi bi-grid me-2"></i> Semua
                            </button>
                        </a>
                        <a href="{{ route($prefix . 'tiket.history', array_merge(request()->query(), ['status' => 'Closed'])) }}">
                            <button type="button" class="btn btn-sm px-4 {{ request('status') == 'Closed' ? 'btn-success' : 'btn-white border' }}">
                                <span class="text-success me-2">●</span> Closed
                            </button>
                        </a>
                        <a href="{{ route($prefix . 'tiket.history', array_merge(request()->query(), ['status' => 'Rejected'])) }}">
                            <button type="button" class="btn btn-sm px-4 {{ request('status') == 'Rejected' ? 'btn-danger' : 'btn-white border' }}">
                                <span class="text-danger me-2">●</span> Rejected
                            </button>
                        </a>
                    </div>
                </div>
                <div class="col d-flex justify-content-end align-items-center">
                    <form action="{{ route($prefix.'tiket.history') }}" method="GET">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="d-flex justify-content-end align-items-center">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control rounded-pill ps-4 pe-5 shadow-sm border-0 bg-light"
                                placeholder="Cari tiket..."
                                style="margin-right: 3px;">
                            <button
                                type="submit"
                                class="btn btn-sm btn-primary ms-2"
                                style="border-radius: 50%; height: fit-content;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row">
                @forelse($tikets as $tickets)
                @php
                $priorityStyle = match($tickets->priority?->name) {
                'Emergency' => 'text-danger',
                'Urgent' => 'text-warning',
                'Normal' => 'text-success',
                default => 'text-secondary',
                };

                $verificationStyle = match($tickets->verification_status) {
                'pending' => 'text-warning',
                'verified' => 'text-success',
                'rejected' => 'text-danger',
                default => 'text-secondary',
                };
                $nodepriorityStyle = match($tickets->priority?->name) {
                'Emergency' => 'bg-danger',
                'Urgent' => 'bg-warning',
                'Normal' => 'bg-success',
                default => 'bg-secondary',
                };
                $statusStyle = match($tickets->status){
                'Open' => 'btn-primary',
                'Accept' => 'btn-info',
                'Assigned' => 'btn-warning',
                'Cancel' => 'btn-warning',
                'Resolved' => 'btn-success',
                'Closed' => 'btn-secondary',
                'Rejected' => 'btn-danger',
                'Reopen' => 'btn-danger',
                default => 'btn-secondary',
                };
                @endphp
                <div class="col-md-6">
                    <div class="card mb-3 card-shadow-2">
                        <div class="card-header bg-transparent">
                            <div class="row">
                                <div class="col-md-8 d-flex align-items-center">
                                    <i class="icon-ticket bi bi-ticket-detailed rounded-5 d-md-flex align-items-center justify-content-center d-none"></i>
                                    <span class=" text-dark" style="font-size: 0.85rem; letter-spacing: 0.3px; font-weight:bold;">{{$tickets->ticket_code}}</span>
                                </div>
                                <div class="col-md-4 d-flex justify-content-md-end">
                                    <a href="{{route($prefix . 'tiket.show', $tickets->id) }}" class="btn btn-outline-primary btn-sm d-flex " style="height:fit-content">Lihat Detail Tiket</a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-8 ms-2">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;">Judul Tiket :</div>
                                    <div class="text-dark" style="font-size: 0.85rem; font-weight: bold;">{{$tickets->title}}</div>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row d-flex" style="margin-bottom:0;">
                                <div class="col-md-4 colums-card-body">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-menu-app"></i> Aplikasi</div>
                                    <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"><i class="bi bi-tags"></i> {{$tickets->application->name}}</div>
                                </div>
                                <div class="col-md-4 colums-card-body">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-bookmark-star"></i> Prioritas</div>
                                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <span class="{{$nodepriorityStyle}}" style="margin-right:5px;width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                                        <span class="{{ $priorityStyle }} fw-bold" style="font-size: 0.75rem; font-weight:bolder;">{{$tickets->priority?->name ?? 'Belum Ditentukan'}}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-stopwatch"></i> kinerja Penyelesaian</div>
                                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <span class="{{$nodepriorityStyle}}" style="margin-right:5px;width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                                        <span class="{{ $priorityStyle }}" style="font-size: 0.75rem; font-weight:bolder;">{{$tickets->priority?->name ?? 'Belum Ditentukan'}}</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row d-flex" style="margin-bottom:0;">
                                <div class="col-md-4 colums-card-body">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-menu-app"></i> Dibuat Pada</div>
                                    <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"> <i class="bi bi-clock-history" style="margin-right: 3px;"></i>
                                        {{ $tickets->created_at ? $tickets->created_at->format('d F Y') : '-' }}
                                    </div>
                                </div>
                                <div class="col-md-4 colums-card-body">
                                    @if($tickets->rejected_at)
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-calendar-minus"></i> Ditolak Pada</div>
                                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-clock-history" style="margin-right: 3px;"></i>
                                        <span class="text-danger fw-bold" style="font-size: 0.75rem; font-weight:bolder;">{{ $tickets->rejected_at ? $tickets->rejected_at->format('d F Y') : '-' }}</span>
                                    </div>
                                    @else
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-calendar-check"></i> Tiket Selesai</div>
                                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-clock-history" style="margin-right: 3px;"></i>
                                        <span class="text-secondary fw-bold" style="font-size: 0.75rem; font-weight:bolder;">{{ $tickets->closed_at ? $tickets->closed_at->format('d F Y') : '-' }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-stopwatch"></i> Durasi Pengerjaan</div>
                                    <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-clock-history" style="margin-right: 3px;"></i>
                                        <span class="text-secondary fw-bold" style="font-size: 0.75rem; font-weight:bolder;">
                                            {{$menit = $tickets->assignment?->formattedWorkDuration() ?? 0;}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="row">
                                <div class="col-md-4 colums-card-body">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-person-fill-gear"></i> Petugas Teknis</div>
                                    <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"><i>*</i> {{ $tickets->assignment->technician->name ?? '-'}}</div>
                                </div>
                                <div class="col-md-4 colums-card-body">
                                    <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-person-vcard-fill"></i> PIC</div>
                                    <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"><i>*</i> {{ $tickets->assignment?->admin?->name ?? '-' }}</div>
                                </div>
                                <div class="col-md d-flex justify-content-md-end">
                                    <i class="btn btn-sm rounded-5 {{$statusStyle}}" style="cursor:default; height:fit-content;">{{$tickets['status']}}</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @include('_layouts.components.empty_state')
                @endforelse
            </div>
            {{ $tikets->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@push('scripts')
@endpush
@endsection