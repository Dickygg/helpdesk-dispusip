@extends('_layouts.app')
@section('title', 'Daftar Assignmen Berjalan')
@section('page-title', 'Daftar Berjalan')
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
        background: #cce1f5ff;
        color: #4e87b8ff;
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

    .badge-priority {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
        min-width: 90px;
        text-align: center;
    }

    .priority-Normal {
        background: #E8FFF3;
        color: #198754;
    }

    .priority-Urgent {
        background: #FFF8E1;
        color: #F59E0B;
    }

    .priority-Emergency {
        background: #FDEBEC;
        color: #DC3545;
    }

    .badge-priority-high {
        background: #ffe5e5;
        color: #dc3545;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .assignment-priority-card {
        background: #fff8f8;
        border: 1px solid #ffd9d9;
        border-radius: 12px;
        padding: 20px;
        transition: .3s;
        height: 100%;
    }

    .ticket-code {
        color: #4f6df5;
        font-weight: 700;
    }



    .btn-arrow {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid #ffd9d9;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #dc3545;
        background: #fff;
        text-decoration: none;
    }

    .btn-arrow:hover {
        background: #dc3545;
        color: white;
        text-decoration: none;
    }

    .assignment-slider {
        display: flex;
        overflow-x: hidden;
        gap: 15px;
        scroll-behavior: smooth;
    }

    .assignment-item {
        flex: 0 0 calc(33.333% - 10px);
    }

    .assignment-nav {
        position: absolute;
        top: 40%;
        z-index: 10;
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
    }

    .assignment-nav.prev {
        left: -18px;
    }

    .assignment-nav.next {
        right: -18px;
    }

    .assignment-nav:hover {
        background: #f8f9fa;
    }

    @media (max-width: 768px) {
        .assignment-item {
            flex: 0 0 100%;
        }
    }

    @media (max-width:767.98px) {
        .colums-card-body {
            border-right: none;
        }
    }
</style>
@endpush

<div class="container-fluid">
    <!-- card stats -->
    <div class="row">
        <!-- Total Assignment -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-3">
            <a href="{{ route( 'assignment.petugas.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ !request('condition') ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-folder-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Total Assignment</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['assigntotal'] }}</div>
                            <div class="text-muted title-cardtiket">Assignment</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Selesai -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-3">
            <a href="{{ route('assignment.petugas.index', array_merge(request()->query(), ['condition' => 'Resolved'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('condition') == 'Resolved' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-check-circle-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Selesai</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['total_selesai'] }}</div>
                            <div class="text-muted title-cardtiket">Resolved</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Diproses -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-3">
            <a href="{{ route('assignment.petugas.index', array_merge(request()->query(), ['condition' => 'In Progress'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('condition') == 'In Progress' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-arrow-repeat text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Diproses</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['total_diproses'] }}</div>
                            <div class="text-muted title-cardtiket">In Progress</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Menuju Deadline -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-3">
            <a href="{{ route( 'assignment.petugas.index', array_merge(request()->query(),['condition' => 'upcoming'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('condition') == 'upcoming' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-clock-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Menuju Deadline</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['menuju_deadline'] }}</div>
                            <div class="text-muted title-cardtiket">Dalam 24 jam</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Over Deadline -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-3">
            <a href="{{ route( 'assignment.petugas.index', array_merge(request()->query(),['condition' => 'overDuetime'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('condition') == 'overDuetime' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-exclamation-circle-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Overdue</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['overDuetime'] }}</div>
                            <div class="text-muted title-cardtiket">Belum Selesai</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Reopen total -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-3">
            <a href="{{ route( 'assignment.petugas.index', array_merge(request()->query(),['condition' => 'Reopen'])) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('condition') == 'Reopen' ? 'active' : '' }}">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-arrow-repeat text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Tiket Reopen</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['total_reopen'] }}</div>
                            <div class="text-muted title-cardtiket">Belum Sesuai</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <!-- filter data -->
    <form action="{{ route('assignment.petugas.index')}}" method="GET">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 mb-2">
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
                                    <option value="upcoming">Besok</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="overdue">Overdue</option>
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
                            <div class="col-lg  col-md-12 d-flex justify-content-end mt-md-2">
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                        class="btn btn-primary rounded-3 shadow-sm">
                                        <i class="bi bi-funnel me-1"></i>
                                        Filter
                                    </button>
                                    <a href="{{route('assignment.petugas.export',request()->query())}}"
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
    <!-- pioritas hari ini -->
    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="fw-bold text-danger" style="font-size: 0.90rem; font-weight: bold;">Tiket Mendekati Deadline Atau Tiket Lewat Deadline yang belum Selesai!.</div>
                <i class="bi bi-exclamation-circle text-danger"></i>
            </div>
            <hr class="mt-1 mb-1">
            <div class="d-flex">
                <button type="button" class="assignment-nav prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="assignment-nav next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="position-relative mt-1">
                <div class="assignment-slider" id="assignmentSlider">
                    @foreach($deadline as $d)
                    <div class="assignment-item">
                        <div class="assignment-priority-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge-priority-high">
                                    Important
                                </span>
                            </div>
                            <div class="mt-1">
                                <div class="fw-bold text-primary" style="font-size: 1rem; font-weight: bold;">{{$d->ticket?->ticket_code}}</div>
                                <div class="fw-bold text-secondary" style="font-size: 0.90rem; font-weight: bold;">{{$d->ticket?->title}}</div>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt"></i>
                                    Deadline : {{ $d->ticket?->due_date ? $d->ticket?->due_date->format('d F Y, H:i') : '-'}}
                                </small>
                            </div>
                            <a href="{{route('assignment.petugas.prosesAssignment',$d->id)}}" class="btn btn-outline-danger btn-sm btn-block ">
                                Kerjakan Sekarang
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- data table-->
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
                                    <th>Assign Oleh</th>
                                    <th>Aplikasi</th>
                                    <th>Prioritas</th>
                                    <th>Tipe Tiket</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
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
                                'Reopen' => 'status-reopen',
                                default => ''
                                };

                                $pioritystyle = match($r->ticket?->priority->name ?? '') {
                                'Normal' => 'priority-Normal',
                                'Urgent' => 'priority-Urgent',
                                'Emergency' => 'priority-Emergency',
                                default => 'priority-Normal'
                                };
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $r->ticket?->ticket_code }}</td>
                                    <td>{{ $r->admin?->username }}</td>
                                    <td>{{ $r->ticket?->application->name }}</td>
                                    <td>
                                        <span class="badge-priority {{ $pioritystyle }}">
                                            <i class="bi bi-flag-fill"></i> {{ $r->ticket?->priority->name ?? 'Belum Ditentukan' }}
                                        </span>
                                    </td>
                                    <td>{{ $r->ticket?->tickettype->name }}</td>
                                    <td>
                                        <span class="badge-status {{ $statusstyle }}">
                                            {{ $r->ticket?->status }}
                                        </span>
                                    </td>
                                    <td>{{ $r->ticket?->due_date ? $r->ticket?->due_date->format('d F Y, H:i') : '-'}}</td>
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
                                                        href="{{route('assignment.petugas.show', $r->id)}}">
                                                        <i class="bi bi-eye text-primary me-2"></i>
                                                        Detail Assignment
                                                    </a>
                                                </li>
                                                <!-- Proses -->
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{route('assignment.petugas.prosesAssignment', $r->id)}}">
                                                        <i class="bi bi-person-check text-info me-2"></i>
                                                        Kerjakan Assignment
                                                    </a>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const slider = document.getElementById('assignmentSlider');

        document.querySelector('.next').addEventListener('click', function() {
            slider.scrollBy({
                left: slider.offsetWidth,
                behavior: 'smooth'
            });
        });

        document.querySelector('.prev').addEventListener('click', function() {
            slider.scrollBy({
                left: -slider.offsetWidth,
                behavior: 'smooth'
            });
        });

    });
</script>
@endpush
@endsection