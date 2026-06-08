@extends('_layouts.app')
@section('title', 'Daftar Tiket Berjalan')
@section('page-title', 'Daftar Tiket Berjalan')
@section('content')
@push('styles')
<style>
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

    .card-filter {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .card-filter:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        background-color: #0d6dfd51;
    }

    .card-filter.active {
        background-color: #0d6dfd51;
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
        min-width: 75px;
        text-align: center;
        letter-spacing: 0.3px;
    }

    .status-open {
        background: #E8F1FF;
        color: #0D6EFD;
    }

    .status-accept {
        background: #E7F8F0;
        color: #198754;
    }

    .status-assigned {
        background: #EEF1F4;
        color: #6C757D;
    }

    .status-progress {
        background: #FFF4E5;
        color: #F59E0B;
    }

    .status-resolved {
        background: #E8FFF3;
        color: #20C997;
    }

    .status-closed {
        background: #E9ECEF;
        color: #495057;
    }

    .status-rejected {
        background: #FDEBEC;
        color: #DC3545;
    }

    .status-reopen {
        background: #FDEBEC;
        color: #DC3545;
    }

    .badge-priority-default {
        padding: 5px 5px;
        border-radius: 30px;
        font-size: 0.70rem;
        font-weight: 600;
        display: inline-block;
        /* min-width: 90px; */
        text-align: center;
    }

    .badge-priority {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
        min-width: 90px;
        text-align: center;
    }

    .priority-default {
        background: #9290eeff;
        color: #ffffffff;
        font-size: 0.75rem;
    }

    .priority-normal {
        background: #E8FFF3;
        color: #198754;
    }

    .priority-urgent {
        background: #FFF8E1;
        color: #F59E0B;
    }

    .priority-emergency {
        background: #FDEBEC;
        color: #DC3545;
    }

    @media (max-width:767.98px) {
        .colums-card-body {
            border-right: none;
        }
    }
</style>
@endpush

@php
$prefix = auth()->user()->hasRole('super admin') ? 'sa.' : '';
@endphp

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 card-costum p-3">
                <div class="mb-3">
                    <div class="fw-bold text-primary" style="font-size: 0.85rem; font-weight: bold;">Alur Sistem Tiket</div>
                </div>
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
                                <div class="bg-dark bg-opacity-10 icon-alur"> <i class="bi bi-lock-fill text-white"></i> </div>
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

    <div class="row">
        <!-- Total Ticket -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <a href="{{ route($prefix . 'admin.tiket.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ !request('status') ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-folder-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Total Ticket</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tikettotal }}</div>
                            <div class="text-muted title-cardtiket">Semua tiket</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Open -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <a href="{{ route($prefix . 'admin.tiket.index', array_merge(request()->query(), ['status' => 'Open'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'Open' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-archive-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Open</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats->firstWhere('status', 'Open')->total ?? 0 }}</div>
                            <div class="text-muted title-cardtiket">Tiket Baru</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Accept -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <a href="{{ route($prefix . 'admin.tiket.index', array_merge(request()->query(), ['status' => 'Accept'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'Accept' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-check-circle-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket" style="white-space: nowrap;">Accept</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats->firstWhere('status', 'Accept')->total ?? 0 }}</div>
                            <div class="text-muted title-cardtiket">Diverifikasi</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Assigned -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <a href="{{ route($prefix . 'admin.tiket.index', array_merge(request()->query(), ['status' => 'Assigned'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'Assigned' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-person-fill-check text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Assigned</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats->firstWhere('status', 'Assigned')->total ?? 0 }}</div>
                            <div class="text-muted title-cardtiket">Ditugaskan</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- In Progress -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <a href="{{ route($prefix . 'admin.tiket.index', array_merge(request()->query(), ['status' => 'In Progress'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'In Progress' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-arrow-repeat text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Progres</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats->firstWhere('status', 'In Progress')->total ?? 0 }}</div>
                            <div class="text-muted title-cardtiket">Dikerjakan</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Resolved -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <a href="{{ route($prefix . 'admin.tiket.index', array_merge(request()->query(), ['status' => 'Resolved'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'Resolved' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-check-circle-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Resolved</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats->firstWhere('status', 'Resolved')->total ?? 0 }}</div>
                            <div class="text-muted title-cardtiket">Selesai</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <form action="{{ route($prefix . 'admin.tiket.index')}}" method="GET">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-end g-3">
                            <!-- Tanggal Dari -->
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Dari
                                </label>
                                <input type="date"
                                    name="start_date"
                                    class="form-control rounded-3 shadow-sm border-0">
                            </div>
                            <!-- Tanggal Sampai -->
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Sampai
                                </label>
                                <input type="date"
                                    name="end_date"
                                    class="form-control rounded-3 shadow-sm border-0">
                            </div>
                            <!-- Search aplikasi-->
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Aplikasi
                                </label>
                                <select class="form-select form-control" name="id_aplikasi" id="id_aplikasi">
                                    <option value="" selected>Semua Aplikasi</option>
                                    @foreach($aplikasi as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                    @endforeach
                                </select>
                                @error('id_aplikasi')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                            <!-- seacrh deadline -->
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Deadline
                                </label>
                                <select class="form-select form-control" name="deadline_filter" id="deadline_filter">
                                    <option value="" selected>Semua Deadline</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="overdue">Overdue</option>
                                    <option value="upcoming">Hampir Telat</option>
                                </select>
                                @error('id_aplikasi')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                            <!-- tipe tiket -->
                            <div class="col-lg-3 col-md-6 mt-2">
                                <label class="form-label fw-semibold text-secondary">
                                    Tipe Tiket
                                </label>
                                <select class="form-select form-control" name="ticket_type_id" id="ticket_type_id">
                                    <option value="" selected>Semua Tiket Tipe</option>
                                    @foreach($tipetiket as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                    @endforeach
                                </select>
                                @error('id_aplikasi')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Tombol -->
                            <div class="col-lg col-md-12 d-flex justify-content-end mt-md-2">
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                        class="btn btn-primary rounded-3 shadow-sm">
                                        <i class="bi bi-funnel me-1"></i>
                                        Filter
                                    </button>
                                    <a href="{{ route($prefix .'admin.tiket.export', request()->query()) }}"
                                        class="btn btn-outline-success rounded-3 shadow-sm">
                                        <i class="bi bi-download me-1"></i>
                                        Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-md mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Dispusip<span class="text-info">Helpdesk.</span></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Tiket</th>
                                    <th>Pemohon</th>
                                    <th>Judul</th>
                                    <th>Tipe Ticket</th>
                                    <th>Aplikasi</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $r)
                                @php
                                $statusstyle = match($r->status) {
                                'Open' => 'status-open',
                                'Accept' => 'status-accept',
                                'Assigned' => 'status-assigned',
                                'In Progress' => 'status-progress',
                                'Resolved' => 'status-resolved',
                                'Closed' => 'status-closed',
                                'Rejected' => 'status-rejected',
                                'Reopen' => 'status-reopen',
                                default => ''
                                };

                                $pioritystyle = match($r->priority->name ?? '') {
                                'Normal' => 'badge-priority priority-normal',
                                'Urgent' => 'badge-priority priority-urgent',
                                'Emergency' => 'badge-priority priority-emergency',
                                default => 'badge-priority-default priority-default'
                                };
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $r->ticket_code }}</td>
                                    <td>{{ $r->user->name }}</td>
                                    <td style="max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $r->title }}
                                    </td>
                                    <td>{{ $r->tickettype?->name ?? 'Belum ditentukan' }}</td>
                                    <td>{{ $r->application->name }}</td>
                                    <td>
                                        <span class="{{ $pioritystyle }}">
                                            <i class="bi bi-flag-fill"></i> {{ $r->priority->name ?? 'Belum Ditentukan' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $statusstyle }}">
                                            {{ $r->status }}
                                        </span>
                                    </td>
                                    <td>{{ $r->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary rounded-3"
                                                type="button"
                                                data-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                <!-- Detail -->
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route($prefix . 'admin.tiket.show', $r->id) }}">
                                                        <i class="bi bi-eye text-primary me-2"></i>
                                                        Detail Ticket
                                                    </a>
                                                </li>
                                                <!-- Proses -->
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route($prefix . 'admin.tiket.proses', $r->id) }}">
                                                        <i class="bi bi-person-check text-info me-2"></i>
                                                        Proses Tiket
                                                    </a>
                                                </li>
                                                <!-- <li>
                                                    <hr class="dropdown-divider">
                                                </li> -->
                                                <!-- Hapus -->
                                                <!-- <li>
                                                    <form action="{{ route($prefix . 'admin.tiket.destroy', $r->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus ticket ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i>
                                                            Hapus Ticket
                                                        </button>
                                                    </form>
                                                </li> -->
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@endpush
@endsection