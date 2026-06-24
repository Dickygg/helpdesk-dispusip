@extends('_layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

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
        padding: 4px 5px;
        border-radius: 30px;
        font-size: 0.68rem;
        font-weight: 600;
        display: inline-block;
        min-width: 70px;
        text-align: center;
        margin-bottom: 0;
        margin-top: 0;
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

    @media (min-width: 992px) {
        .card-newtiket {
            height: 430px;
        }

        .card-deadline {
            height: 430px;
        }

    }
</style>
@endpush
@php
$prefix = auth()->user()->hasRole('super admin') ? 'sa.' : '';
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg mb-4" style=" border-radius:20px;background: linear-gradient(135deg, #0b3778ff, #0e211aff); color:white;">
                <div class="card-body p-4">
                    <div class="row d-flex justify-content-between align-items-start">
                        <div class="col-12 col-md-10">
                            <small class="text-white-50 text-uppercase" id="greeting">
                            </small>

                            <h3 class="font-weight-bold mt-2 mb-2">
                                {{Auth::user()->name}}
                            </h3>

                            <p class="mb-2 text-white-50">
                                Pantau performa layanan helpdesk, penyelesaian tiket,
                                dan statistik Assignment.
                            </p>

                            <form method="GET" action="{{ route($prefix.'admin.dashboard') }}">
                                <div class="d-flex flex-wrap align-items-end">

                                    <div class="mr-2 mb-2">
                                        <label class="text-white-50 mb-1 small">
                                            Dari Tanggal
                                        </label>
                                        <input
                                            type="date"
                                            name="start_date"
                                            value="{{ request('start_date') }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="mr-2 mb-2">
                                        <label class="text-white-50 mb-1 small">
                                            Sampai Tanggal
                                        </label>
                                        <input
                                            type="date"
                                            name="end_date"
                                            value="{{ request('end_date') }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="mb-2">
                                        <button type="submit" class="btn btn-light btn-sm mr-2">
                                            <i class="fas fa-filter"></i>
                                            Filter
                                        </button>

                                        <a href="{{ route($prefix.'admin.dashboard') }}"
                                            class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-sync-alt"></i>
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="col-12 col-sm-2">
                            <div class="bg-white p-2 shadow-sm" style="border-radius:12px;color:#333; width:fit-content;">
                                <small class="text-muted d-block">
                                    Hari Ini
                                </small>
                                <strong>
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                                </strong>
                            </div>
                            <!-- <div class="mt-3 text-white-50 d-flex justify-content-center">
                                <a href="#" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-sync-alt"></i>
                                    Refresh Data
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Card Ticket Bedasarkan Status -->
    <div class="row">
        <!-- Total Ticket -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">

            <div class="card border-0 shadow-sm rounded-4 card-filter">
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

        </div>
        <!-- Open -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">

            <div class="card border-0 shadow-sm rounded-4 card-filter">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                        <i class="bi bi-archive-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                    </div>
                    <div>
                        <div class="text-secondary title-cardtiket">Open</div>
                        <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats['Open'] ?? 0 }}</div>
                        <div class="text-muted title-cardtiket">Tiket Baru</div>
                    </div>
                </div>
            </div>

        </div>
        <!-- In Progress -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-filter">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                        <i class="bi bi-arrow-repeat text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                    </div>
                    <div>
                        <div class="text-secondary title-cardtiket">Progres</div>
                        <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats['In Progress'] ?? 0 }}</div>
                        <div class="text-muted title-cardtiket">Dikerjakan</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Closed -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-filter">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                        <i class="bi bi-check-circle-fill text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                    </div>
                    <div>
                        <div class="text-secondary title-cardtiket" style="white-space: nowrap;">Closed</div>
                        <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats['Closed'] ?? 0 }}</div>
                        <div class="text-muted title-cardtiket">Tiket Selesai</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Assigned -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-filter">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                        <i class="bi bi-person-fill-check text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                    </div>
                    <div>
                        <div class="text-secondary title-cardtiket">Rejected</div>
                        <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $tiketstats['Rejected'] ?? 0 }}</div>
                        <div class="text-muted title-cardtiket">Tiket</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2 mt-lg-0">
        <div class="col-12 col-lg-5">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-list-task"></i> Tiket Masuk Terbaru
                    </h6>
                </div>

                <div class="card-body py-4 tiket card-newtiket">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode Tiket</th>
                                    <th>Aplikasi</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newtiket as $t)
                                <tr>
                                    <td class="text-primary" style="font-size: 0.85rem; font-weight: bold;">
                                        {{$t->ticket_code}}
                                    </td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{$t->application?->name}}</td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{ $t->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 28px;">
                        <a href="{{route($prefix.'admin.tiket.index')}}" class="text-primary " style="font-size: 0.7rem; font-weight: bold;">Lihat Semua -></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-danger" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-alarm"></i> Tiket Deadline
                    </h6>
                </div>
                <div class="card-body py-4 card-deadline">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode Tiket</th>
                                    <th>Tipe Ticket</th>
                                    <th>Aplikasi</th>
                                    <th>Prioritas</th>
                                    <th>Deadline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deadlinetiket as $d)
                                @php
                                $pioritystyle = match($d->priority->name ?? '') {
                                'Normal' => 'badge-priority priority-normal',
                                'Urgent' => 'badge-priority priority-urgent',
                                'Emergency' => 'badge-priority priority-emergency',
                                default => 'badge-priority-default priority-default'
                                };
                                @endphp
                                <tr>
                                    <td class="text-primary" style="font-size: 0.85rem; font-weight: bold;">{{$d->ticket_code}}</td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{$d->tickettype?->name}}</td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{$d->application?->name}}</td>
                                    <td>
                                        <span class="{{ $pioritystyle }}">
                                            <i class="bi bi-flag-fill"></i> {{ $d->priority->name ?? 'Belum Ditentukan' }}
                                        </span>
                                    </td>
                                    <td class="text-danger" style="font-size: 0.85rem; font-weight: bold;">{{ $d->due_date->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{route($prefix.'admin.tiket.index')}}" class="text-primary" style="font-size: 0.7rem; font-weight: bold;">Lihat Semua -></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <!-- Chart Tiket perbulan -->
        <div class="col-12 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-bar-chart-line-fill"></i> Statistik Tiket per Bulan
                    </h6>
                </div>

                <div class="card-body">
                    <div style="height:200px;">
                        <canvas id="ticketChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- presentase Tipe Tiket Masuk -->
        <div class="col-12 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header  bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-bar-chart-line-fill"></i> Presentase Tipe Tiket Masuk
                    </h6>
                </div>
                <div class="card-body d-sm-flex flex-sm-column">
                    <div class="d-flex align-items-center flex-column flex-md-row" style="margin-bottom: 40px;">
                        {{-- Chart --}}
                        <div style="position: relative; width: 160px; height: 160px; flex-shrink: 0;">
                            <canvas id="typetikett"></canvas>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                <div style="font-size: 1.2rem; font-weight: 700; line-height: 1.2;">{{ $typetikett['total'] }}</div>
                                <div style="font-size: 0.7rem; color: #6c757d;">Total</div>
                            </div>
                        </div>

                        {{-- Legenda --}}
                        <div class="small ml-md-3 mt-3 mt-md-0" style="flex: 1; width: 100%;">
                            @php
                            $colors = ['#e74a3b', '#f6c23e', '#36b9cc', '#1cc88a', '#2c399aff', '#5a5c69'];
                            @endphp

                            @foreach($typetikett['labels'] as $index => $label)
                            @php
                            $count = $typetikett['data'][$index];
                            $percent = $typetikett['total'] > 0 ? round(($count / $typetikett['total']) * 100, 1) : 0;
                            $color = $colors[$index] ?? '#adb5bd';
                            @endphp
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <span style="display:inline-block; width:12px; height:12px; border-radius:2px; background:{{ $color }}; margin-right:8px; flex-shrink:0;"></span>
                                    <span>{{ $label }}</span>
                                </div>
                                <span class="text-muted ml-2">{{ $count }} ({{ $percent }}%)</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <!-- presentase tingkat Prioritas -->
        <div class="col-12 col-lg-4">
            <div class="card shadow mb-2">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-bar-chart-line-fill"></i> Presentase Tingkat Prioritas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center flex-column flex-md-row">
                        {{-- Chart --}}
                        <div style="position: relative; width: 160px; height: 160px; flex-shrink: 0;">
                            <canvas id="priorityChart"></canvas>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                <div style="font-size: 1.2rem; font-weight: 700; line-height: 1.2;">{{ array_sum($priorityChart['data']) }}</div>
                                <div style="font-size: 0.7rem; color: #6c757d;">Total</div>
                            </div>
                        </div>
                        {{-- Legenda --}}
                        <div class="small ml-md-3 mt-3 mt-md-0" style="flex: 1; width: 100%;">
                            @php
                            $total = array_sum($priorityChart['data']);
                            $colors = ['#e74a3b', '#f6c23e', '#36b9cc', '#1cc88a'];
                            @endphp

                            @foreach($priorityChart['labels'] as $index => $label)
                            @php
                            $count = $priorityChart['data'][$index];
                            $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $color = $colors[$index] ?? '#adb5bd';
                            @endphp
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <span style="display:inline-block; width:12px; height:12px; border-radius:2px; background:{{ $color }}; margin-right:8px; flex-shrink:0;"></span>
                                    <span>{{ $label }}</span>
                                </div>
                                <span class="text-muted ml-2">{{ $count }} ({{ $percent }}%)</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- presentase penyelesiaan -->
        <div class="col-12 col-lg-4">
            <div class="card shadow border-0 mb-2">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-bar-chart-line-fill"></i> Presentase Penyelesaian
                    </h6>
                </div>

                <div class="card-body py-4">

                    <div class="row text-center">

                        <!-- Tepat Waktu -->
                        <div class="col-md-4 border-right">
                            <div class="mb-3" style="font-size: 0.8rem; font-weight: bold;">
                                <i class="fas fa-check-circle text-success fa-3x"></i>
                            </div>

                            <h5 class="text-secondary mb-3" style="font-size: 0.7rem; font-weight: bold;">
                                Tepat Waktu
                            </h5>

                            <h1 class="font-weight-bold" style="font-size: 1.3rem; font-weight: bold;">
                                {{ $sla['ontime'] }}
                            </h1>

                            <h3 class="text-muted" style="font-size: 1.3rem; font-weight: bold;">
                                {{ $sla['ontime_percent'] }}%
                            </h3>
                        </div>

                        <!-- Terlambat -->
                        <div class="col-md-4 border-right">
                            <div class="mb-3" style="font-size: 0.8rem; font-weight: bold;">
                                <i class="far fa-clock text-danger fa-3x"></i>
                            </div>

                            <h5 class="text-secondary mb-3" style="font-size: 0.7rem; font-weight: bold;">
                                Terlambat
                            </h5>

                            <h1 class="font-weight-bold" style="font-size: 1.3rem; font-weight: bold;">
                                {{ $sla['late'] }}
                            </h1>

                            <h3 class="text-muted" style="font-size: 1.3rem; font-weight: bold;">
                                {{ $sla['late_percent'] }}%
                            </h3>
                        </div>

                        <!-- Persentase SLA -->
                        <div class="col-md-4">
                            <div class="mb-3" style="font-size: 0.8rem; font-weight: bold;">
                                <i class="fas fa-chart-pie text-primary fa-3x"></i>
                            </div>

                            <h5 class="text-secondary mb-3" style="font-size: 0.7rem; font-weight: bold;">
                                Total Presentase
                            </h5>

                            <h1 class="font-weight-bold text-primary" style="font-size: 1.3rem; font-weight: bold;">
                                {{ $sla['sla_percent'] }}%
                            </h1>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow mb-2">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-bar-chart-line-fill"></i> Presentase Tiket Berdasarkan Aplikasi
                    </h6>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-center flex-column flex-md-row">

                        {{-- Chart --}}
                        <div style="position: relative; width: 160px; height: 160px; flex-shrink: 0;">
                            <canvas id="applicationChart"></canvas>

                            <div style="position: absolute;
                                top: 50%;
                                left: 50%;
                                transform: translate(-50%, -50%);
                                text-align: center;
                                pointer-events: none;">

                                <div style="font-size: 1.2rem; font-weight: 700; line-height: 1.2;">
                                    {{ array_sum($applicationChart['data']) }}
                                </div>

                                <div style="font-size: 0.7rem; color: #6c757d;">
                                    Total
                                </div>
                            </div>
                        </div>

                        {{-- Legenda --}}
                        <div class="small ml-md-3 mt-3 mt-md-0" style="flex:1; width:100%;">

                            @php
                            $total = array_sum($applicationChart['data']);

                            $colors = [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b',
                            '#858796',
                            '#5a5c69'
                            ];
                            @endphp

                            @foreach($applicationChart['labels'] as $index => $label)

                            @php
                            $count = $applicationChart['data'][$index];
                            $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                            $color = $colors[$index] ?? '#adb5bd';
                            @endphp

                            <div class="d-flex align-items-center justify-content-between mb-2">

                                <div class="d-flex align-items-center">
                                    <span style="
                                    display:inline-block;
                                    width:12px;
                                    height:12px;
                                    border-radius:2px;
                                    background:{{ $color }};
                                    margin-right:8px;">
                                    </span>

                                    <span>{{ $label }}</span>
                                </div>

                                <span class="text-muted ml-2">
                                    {{ $count }} ({{ $percent }}%)
                                </span>

                            </div>

                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="card shadow border-0 mb-2">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-activity"></i> Aktivitas Terbaru
                    </h6>
                </div>

                <div class="card-body py-4 tiket">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Deskripsi</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Tiket ID</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->event }}</td>
                                    <td>{{ $log->causer->name ?? '-' }}</td>
                                    <td>{{ $log->subject->ticket_code ?? '-' }}</td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada aktivitas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $logs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card shadow border-0 mb-4">
                <div class="card-header bg-white  py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-award-fill"></i> Performa Petugas
                    </h6>
                </div>
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="font-size: 0.95rem; font-weight: bold;">Petugas</th>
                                    <th style="font-size: 0.95rem; font-weight: bold;">Total Pengerjaan</th>
                                    <th style="font-size: 0.95rem; font-weight: bold;">Presentase Waktu Pengerjaan</th>
                                    <th style="font-size: 0.95rem; font-weight: bold;">Presentase Tepat Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignmentstats as $a)
                                @php
                                $hours = round($a->avg_hours);

                                $days = floor($hours / 24);
                                $remainHours = $hours % 24;
                                @endphp
                                <tr>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{$a->name}}</td>
                                    <td style="font-size: 0.85rem; font-weight: bold;" class="text-center">{{ $a->total_assignment }}</td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{ $days }} Hari {{ $remainHours }} Jam</td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">
                                        <div class="d-flex align-items-center">

                                            <div class="progress flex-grow-1 mr-2" style="height:8px;">
                                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated bg-success"
                                                    style="width: 0%; transition: width 1s ease-in-out;"
                                                    data-width="{{ $a->sla_percent }}">
                                                </div>
                                            </div>

                                            <span>{{ $a->sla_percent }}%</span>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak Ada Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
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
@if ($errors->any())
<script>
    $(document).ready(function() {
        $('#pioritybaruModal').modal('show');
    });
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.progress-bar[data-width]').forEach(function(bar) {
            const target = bar.getAttribute('data-width');
            setTimeout(() => bar.style.width = target + '%', 100);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {

        const ctx = document.getElementById('ticketChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: @json($chartData['data']),

                    borderColor: '#4e73df',
                    backgroundColor: '#4e73df',

                    borderWidth: 2,

                    pointRadius: 4,
                    pointHoverRadius: 6,

                    tension: 0.3,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

    });

    var ctx = document.getElementById("priorityChart");

    new Chart(ctx, {
        type: 'doughnut',
        data: {

            datasets: [{
                data: @json($priorityChart['data']),
                backgroundColor: [
                    '#e74a3b', // Emergency
                    '#f6c23e', // Urgent
                    '#36b9cc', // Normal
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            legend: {
                display: false
            }
        }
    });

    const typeCtx = document.getElementById('typetikett').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {

            datasets: [{
                data: @json($typetikett['data']),
                backgroundColor: ['#e74a3b', '#f6c23e', '#36b9cc', '#1cc88a', '#2c399aff', '#5a5c69'],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            cutout: '70%',
            legend: {
                display: false
            }
        }
    });

    const applicationCtx = document.getElementById('applicationChart');

    new Chart(applicationCtx, {
        type: 'doughnut',
        data: {
            labels: @json($applicationChart['labels']),
            datasets: [{
                data: @json($applicationChart['data']),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796',
                    '#5a5c69'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    function updateGreeting() {
        const jam = new Date().getHours();
        let greeting;

        if (jam >= 5 && jam < 11) {
            greeting = 'Selamat Pagi 🌤️';
        } else if (jam >= 11 && jam < 15) {
            greeting = 'Selamat Siang ☀️';
        } else if (jam >= 15 && jam < 18) {
            greeting = 'Selamat Sore 🌇';
        } else {
            greeting = 'Selamat Malam 🌙';
        }

        document.getElementById('greeting').innerText = greeting;
    }

    updateGreeting();
    setInterval(updateGreeting, 3600000); // update tiap 1 menit



    $(document).ready(function() {
        $('#infoModal').modal('show');
    });
</script>
@endpush
@endsection