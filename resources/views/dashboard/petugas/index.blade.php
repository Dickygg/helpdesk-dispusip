@extends('_layouts.app')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')

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
        .card-deadline {
            height: 420px;
        }

        .card-new {
            height: 420px;
        }

        .card-statistik {
            height: 200px;
        }
    }
</style>
@endpush
<div id="section-print">
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

                                <form method="GET" action="{{ route('dashboard.petugas') }}">
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

                                            <a href="{{route('dashboard.petugas')}}"
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
        <div class="row">
            <!-- Total Assignment -->
            <div class="col-lg col-md-4 col-sm-12 mb-0 mb-sm-3">

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
            <!-- Selesai -->
            <div class="col-lg col-md-4 col-sm-12 mb-0 mb-sm-4">

                <div class="card border-0 shadow-sm rounded-4 card-filter">
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

            </div>
            <!-- Diproses -->
            <div class="col-lg col-md-4 col-sm-12 mb-0 mb-sm-4">

                <div class="card border-0 shadow-sm rounded-4 card-filter ">
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

            </div>
            <!-- Over Deadline -->
            <div class="col-lg col-md-4 col-sm-12 mb-0 mb-sm-4">

                <div class="card border-0 shadow-sm rounded-4 card-filter ">
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

            </div>
            <!-- Reopen total -->
            <div class="col-lg col-md-4 col-sm-12 mb-0 mb-sm-4">

                <div class="card border-0 shadow-sm rounded-4 card-filter">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-2 shadow-sm" style="width: 45px; height:45px; border-radius:20px; margin-right:7px;">
                            <i class="bi bi-arrow-repeat text-light d-flex justify-content-center align-items-center" style="font-size: 1.3rem; margin-top: 4px;"></i>
                        </div>
                        <div>
                            <div class="text-secondary title-cardtiket">Tiket Closed</div>
                            <div class="fw-bold" style="font-size: 0.95rem; font-weight: bold;">{{ $getassignstats['total_closed'] }}</div>
                            <div class="text-muted title-cardtiket">Selesaii</div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Rata Rata Pengerjaan -->
            <div class="col-lg col-md-4 col-sm-12 mb-0 mb-sm-4">
                <div class="card border-0 shadow-sm rounded-4 card-filter">
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
        <div class="row mt-2 mt-lg-0">
            <div class="col-12 col-lg-6">
                <div class="card shadow border-0 mb-2">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                            <i class="bi bi-send"></i> Assignment Masuk Terbaru
                        </h6>
                    </div>

                    <div class="card-body py-4 tiket card-new">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Kode Tiket</th>
                                        <th>Assign By</th>
                                        <th>Prioritas</th>
                                        <th>Deadline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($newassignment as $t)

                                    @php
                                    $pioritystyle = match($t->ticket?->priority?->name ?? '') {
                                    'Normal' => 'badge-priority priority-normal',
                                    'Urgent' => 'badge-priority priority-urgent',
                                    'Emergency' => 'badge-priority priority-emergency',
                                    default => 'badge-priority-default priority-default'
                                    };
                                    @endphp
                                    <tr>
                                        <td class="text-primary" style="font-size: 0.85rem; font-weight: bold;">
                                            {{$t->ticket?->ticket_code}}
                                        </td>
                                        <td style="font-size: 0.85rem; font-weight: bold;">{{$t->admin?->name}}</td>
                                        <td>
                                            <span class="{{ $pioritystyle }}">
                                                <i class="bi bi-flag-fill"></i> {{ $t->ticket?->priority->name ?? 'Belum Ditentukan' }}
                                            </span>
                                        </td>
                                        <td style="font-size: 0.85rem; font-weight: bold;">{{ $t->ticket->due_date->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 28px;">
                            <a href="{{route('assignment.petugas.index')}}" class="text-primary " style="font-size: 0.7rem; font-weight: bold;">Lihat Semua -></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow border-0 mb-2">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 text-danger" style="font-size: 0.9rem; font-weight: bold;">
                            <i class="bi bi-alarm"></i> Assignment Mendekati Deadline Deadline
                        </h6>
                    </div>
                    <div class="card-body py-4 card-deadline">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="bg-light">
                                        <th>Kode Tiket</th>
                                        <th>Prioritas</th>
                                        <th>Deadline</th>
                                        <th>Sisa Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deadline as $d)
                                    @php
                                    $pioritystyle = match($d->ticket?->priority->name ?? '') {
                                    'Normal' => 'badge-priority priority-normal',
                                    'Urgent' => 'badge-priority priority-urgent',
                                    'Emergency' => 'badge-priority priority-emergency',
                                    default => 'badge-priority-default priority-default'
                                    };
                                    @endphp
                                    <tr>
                                        <td class="text-primary" style="font-size: 0.85rem; font-weight: bold;">{{$d->ticket->ticket_code}}</td>
                                        <td>
                                            <span class="{{ $pioritystyle }}">
                                                <i class="bi bi-flag-fill"></i> {{ $d->ticket?->priority->name ?? 'Belum Ditentukan' }}
                                            </span>
                                        </td>
                                        <td class="text-danger" style="font-size: 0.85rem; font-weight: bold;">{{ $d->ticket?->due_date->format('d M Y') }}</td>
                                        <td class="text-danger" style="font-size: 0.85rem; font-weight: bold;">{{ $d->ticket?->due_date->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{route('assignment.petugas.index')}}" class="text-primary" style="font-size: 0.7rem; font-weight: bold;">Lihat Semua -></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @php
            $total = $priorityChart['total'];

            $colors = [
            '#e74a3b',
            '#f6c23e',
            '#36b9cc',
            '#1cc88a',
            '#4e73df',
            '#858796'
            ];
            @endphp

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
                                    <div style="font-size: 1.4rem; font-weight: 700;">{{ $total }}</div>
                                    <div style="font-size: .75rem; color: #858796;">Assignment</div>
                                </div>
                            </div>

                            {{-- Legend --}}
                            <div class="small ml-md-3 mt-3 mt-md-0 w-100">

                                @foreach($priorityChart['labels'] as $index => $label)
                                @php
                                $count = $priorityChart['data'][$index];
                                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                $color = $colors[$index % count($colors)];
                                @endphp

                                <div class="d-flex align-items-center justify-content-around mb-2">
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
            <div class="col-12 col-lg-8">
                <div class="card shadow mb-2">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                            <i class="bi bi-bar-chart-line-fill"></i> Statistik Tiket per Bulan
                        </h6>
                    </div>

                    <div class="card-body card-statistik">
                        <div>
                            <canvas id="ticketChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="card shadow border-0 mb-4">
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
                                        <th>Event</th>
                                        <th>Tiket ID</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->event }}</td>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card shadow border-0 mb-4">
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
        </div>
    </div>
    @push('scripts')

    <script>
        async function downloadPDF() {
            const element = document.getElementById('section-print');

            const canvas = await html2canvas(element, {
                scale: 2, // kualitas lebih tinggi
                useCORS: true, // izinkan asset eksternal
                allowTaint: true,
                backgroundColor: '#ffffff'
            });

            const imgData = canvas.toDataURL('image/png');
            const {
                jsPDF
            } = window.jspdf;

            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            pdf.save(`dashboard-petugas.pdf`);
        }

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


        // chart priority
        var ctx = document.getElementById("priorityChart");

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($priorityChart['labels']),
                datasets: [{
                    data: @json($priorityChart['data']),
                    backgroundColor: [
                        '#e74a3b',
                        '#f6c23e',
                        '#36b9cc',
                        '#1cc88a',
                        '#4e73df',
                        '#858796'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    @endpush
    @endsection