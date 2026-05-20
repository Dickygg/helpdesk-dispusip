@extends('_layouts.app')
@section('title', 'Daftar Assigment')
@section('page-title', 'Daftar Assigment')
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

    .badge-priority {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
        min-width: 90px;
        text-align: center;
    }

    .priority-low {
        background: #E8FFF3;
        color: #198754;
    }

    .priority-medium {
        background: #FFF8E1;
        color: #F59E0B;
    }

    .priority-high {
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
    <!-- searchbar -->
    <form action="{{ route($prefix . 'admin.tiket.index')}}" method="GET">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-end g-3">
                            <!-- Tanggal Dari -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Dari
                                </label>
                                <input type="date"
                                    name="start_date"
                                    class="form-control rounded-3 shadow-sm border-0">
                            </div>
                            <!-- Tanggal Sampai -->
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Sampai
                                </label>
                                <input type="date"
                                    name="end_date"
                                    class="form-control rounded-3 shadow-sm border-0">
                            </div>
                            <!-- Search -->
                            <div class="col-lg-4 col-md-6">

                                <label class="form-label fw-semibold text-secondary">
                                    Aplikasi
                                </label>
                                <select class="form-select form-control shadow-sm" name="id_aplikasi" id="id_aplikasi">
                                    <option value="" selected>Semua Aplikasi</option>
                                    @foreach($aplikasi as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                    @endforeach
                                </select>
                                @error('id_aplikasi')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror

                            </div>
                            <div class="col-lg-4 col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    Petugas
                                </label>
                                <select class="form-select form-control shadow-sm" name="id_petugas" id="id_petugas">
                                    <option value="" selected>Semua Petugas</option>
                                    @foreach($petugas as $s)
                                    <option value="{{$s->id}}">{{$s->username}}</option>
                                    @endforeach
                                </select>
                                @error('id_petugas')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-6 mt-md-2">
                                <label class="form-label fw-semibold text-secondary">
                                    Prioritas
                                </label>
                                <select class="form-select form-control shadow-sm" name="id_priority" id="id_priority">
                                    <option value="" selected>Semua Prioritas</option>
                                    @foreach($prioritas as $s)
                                    <option value="{{$s->id}}">{{$s->name}}</option>
                                    @endforeach
                                </select>
                                @error('id_priority')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Tombol -->
                            <div class="col-lg-4 col-md-12 d-flex justify-content-end">
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                        class="btn  btn-primary rounded-3 shadow-sm">
                                        <i class="bi bi-funnel me-1"></i>
                                        Filter
                                    </button>
                                    <a href="#"
                                        class="btn  btn-outline-primary rounded-3 shadow-sm">
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

    <!-- Main Data -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg mb-4">
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
                                    <th>Petugas</th>
                                    <th>Assign Oleh</th>
                                    <th>Aplikasi</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th>Tanggal Diassign</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                @php
                                $statusstyle = match($r->ticket?->status) {
                                'Open' => 'status-open',
                                'Accept' => 'status-accept',
                                'Assigned' => 'status-assigned',
                                'In Progress' => 'status-progress',
                                'Resolved' => 'status-resolved',
                                'Closed' => 'status-closed',
                                'Rejected' => 'status-rejected',
                                default => ''
                                };

                                $pioritystyle = match($r->ticket?->priority->name ?? '') {
                                'Low' => 'priority-low',
                                'Medium' => 'priority-medium',
                                'High' => 'priority-high',
                                default => 'priority-low'
                                };
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $r->ticket?->ticket_code }}</td>
                                    <td>{{ $r->technician?->username }}</td>
                                    <td>{{ $r->admin?->username }}</td>
                                    <td>{{ $r->ticket?->application->name }}</td>
                                    <td>
                                        <span class="badge-priority {{ $pioritystyle }}">
                                            <i class="bi bi-flag-fill"></i> {{ $r->ticket?->priority->name ?? 'Belum Ditentukan' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $statusstyle }}">
                                            {{ $r->ticket?->status }}
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
                                                        href="#">
                                                        <i class="bi bi-eye text-primary me-2"></i>
                                                        Detail Ticket
                                                    </a>
                                                </li>
                                                <!-- Proses -->
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="#">
                                                        <i class="bi bi-person-check text-info me-2"></i>
                                                        Proses Tiket
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <!-- Hapus -->
                                                <li>
                                                    <form action="#"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus ticket ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i>
                                                            Hapus Ticket
                                                        </button>
                                                    </form>
                                                </li>
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