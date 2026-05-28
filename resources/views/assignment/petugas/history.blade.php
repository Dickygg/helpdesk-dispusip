@extends('_layouts.app')
@section('title', 'Daftar Assignmen')
@section('page-title', 'Daftar Assignmen')
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


    .ticket-code {
        color: #4f6df5;
        font-weight: 700;
    }

    .badge-priority-high {
        background: #ffe5e5;
        color: #dc3545;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
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
        <div class="col-lg-3 col-md-4 col-sm-12 mb-0 mb-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-filter">
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
        </div>
        <!-- Bulan Ini -->
        <div class="col-lg-3 col-md-4 col-sm-12 mb-0 mb-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'Resolved' ? 'active' : '' }}">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                        <i class="bi bi-calendar-check text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                    </div>
                    <div>
                        <div class="text-secondary title-cardtiket">Diselesaikan</div>
                        <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['assignmounthtotal'] }}</div>
                        <div class="text-muted title-cardtiket">Bulan ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 mb-0 mb-md-3">
            <div class="card border-0 shadow-sm rounded-4 card-filter {{ request('status') == 'Resolved' ? 'active' : '' }}">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                        <i class="bi bi-clock text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                    </div>
                    <div>
                        <div class="text-secondary title-cardtiket">Rata Pengerjaan</div>
                        <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['avg_work_duration'] }}</div>
                        <div class="text-muted title-cardtiket">Resolved</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- filter data -->
    <form action="{{ route('assignment.petugas.history')}}" method="GET">
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
                            <div class="col-lg-3 col-md-6 mt-md-2">
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
                            <div class="col-lg-12 col-md-12 d-flex justify-content-end mt-md-2">
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                        class="btn btn-primary rounded-3 shadow-sm">
                                        <i class="bi bi-funnel me-1"></i>
                                        Filter
                                    </button>
                                    <a href="#"
                                        class="btn btn-outline-primary rounded-3 shadow-sm">
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
                                    <th>Durasi Pengerjaan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $r)
                                @php
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
                                    @php
                                    $menit = $r->work_duration ?? 0;
                                    $jam = intdiv($menit, 60);
                                    $sisa = $menit % 60;
                                    @endphp
                                    <td> @if($r->work_duration)
                                        {{ $jam > 0 ? $jam . ' jam ' : '' }}{{ $sisa > 0 ? $sisa . ' menit' : '' }}
                                        @else
                                        -
                                        @endif
                                    </td>
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