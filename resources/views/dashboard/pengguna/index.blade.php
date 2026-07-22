@extends('_layouts.app')

@section('title', 'Dashboard Pengguna')
@section('page-title', 'Dashboard Pengguna')

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

    .status-cancel {
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

    @media (min-width: 992px) {
        .card-logs {
            height: 445px;
        }

        .card-ticket {
            height: 410px;
        }

    }
</style>
@endpush
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg mb-3" style=" border-radius:20px;background: linear-gradient(135deg, #0b3778ff, #0e211aff); color:white;">
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

                            <form method="GET" action="{{ route('dashboard.pengguna') }}">
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

                                        <a href="{{ route('dashboard.pengguna') }}"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Total Ticket -->
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-2">

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
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-2">

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
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-2">
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
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-2">
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
        <div class="col-lg col-md-4 col-sm-12 mb-0 mb-md-2">
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
    <div class="row mt-2 mt-md">
        <div class="col-12 col-lg-6">
            <div class="card shadow mb-2">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-activity"></i> Aktivitas Terbaru Tiket
                    </h6>
                </div>
                <div class="card-body p-0">

                    <div class="row d-flex justify-content-center">
                        <div class="col">
                            @forelse($recentActivity as $activity)
                            @php
                            $event = $activity->event ?? '';

                            $config = match(true) {
                            str_contains($event, 'Created') || str_contains($activity->description, 'Dibuat')
                            => ['dot' => '#4e73df'],
                            str_contains($event, 'Updated') || str_contains($event, 'updated')
                            => ['dot' => '#36b9cc'],
                            str_contains($event, 'Assigned')
                            => ['dot' => '#36b9cc'],
                            str_contains($event, 'Mulai Penanganan')
                            => ['dot' => '#f6c23e'],
                            str_contains($event, 'Selesai Penanganan')
                            => ['dot' => '#1cc88a'],
                            str_contains($event, 'Konfirmasi Pengguna') || str_contains($event, 'Konfirmasi Otomatis')
                            => ['dot' => '#1cc88a'],
                            str_contains($event, 'Tolak Konfirmasi')
                            => ['dot' => '#e74a3b'],
                            str_contains($event, 'Verifikasi Tiket')
                            => ['dot' => '#36b9cc'],
                            str_contains($event, 'Menolak Tiket')
                            => ['dot' => '#e74a3b'],
                            default
                            => ['dot' => '#858796'],
                            };

                            $ticketCode = $activity->subject?->ticket_code ?? '-';
                            @endphp

                            <div class="d-flex align-items-start px-3 py-3 border-bottom">

                                {{-- Icon --}}
                                <div class="mr-3 mt-1" style="flex-shrink:0;">
                                    <div style="width: 12px;height: 12px;border-radius: 50%;background-color: {{ $config['dot'] }}; margin-top: 4px;"></div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <p class="mb-0" style="font-size: 0.875rem; font-weight: 600;">
                                                {{ $activity->description }}
                                            </p>
                                            @if($ticketCode !== '-')
                                            <span class="text-primary" style="font-size: 0.8rem;">
                                                {{ $ticketCode }}
                                            </span>
                                            @endif

                                            @if($activity->properties->has('note'))
                                            <p class="mb-0 text-muted" style="font-size: 0.78rem;">
                                                Catatan: {{ $activity->properties['note'] }}
                                            </p>
                                            @endif

                                            @if($activity->properties->has('reason_rejected'))
                                            <p class="mb-0 text-muted" style="font-size: 0.78rem;">
                                                Alasan: {{ $activity->properties['reason_rejected'] }}
                                            </p>
                                            @endif
                                        </div>
                                        <small class="text-muted ml-2" style="white-space:nowrap; font-size: 0.75rem;">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>

                            </div>
                            @empty
                            <div class="text-center text-muted py-4" style="font-size: 0.875rem;">
                                <i class="bi bi-inbox" style="font-size: 1.5rem;"></i>
                                <p class="mt-2 mb-0">Belum ada aktivitas</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow border-0 mb-2">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-list-task"></i> Tiket Terbaru
                    </h6>
                </div>

                <div class="card-body py-4 card-ticket">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kode Tiket</th>
                                    <th>Aplikasi</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newtiket as $t)
                                @php
                                $statusstyle = match($t->status) {
                                'Open' => 'status-open',
                                'Accept' => 'status-accept',
                                'Assigned' => 'status-assigned',
                                'In Progress' => 'status-progress',
                                'Resolved' => 'status-resolved',
                                'Closed' => 'status-closed',
                                'Rejected' => 'status-rejected',
                                'Reopen' => 'status-reopen',
                                'Cancel' => 'status-cancel',
                                default => ''
                                };
                                @endphp
                                <tr>
                                    <td class="text-primary" style="font-size: 0.85rem; font-weight: bold;">
                                        {{$t->ticket_code}}
                                    </td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{$t->application?->name}}</td>
                                    <td>
                                        <span class="badge-status {{ $statusstyle }}">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td style="font-size: 0.85rem; font-weight: bold;">{{ $t->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <a href="{{route('tiket.index')}}" class="text-primary " style="font-size: 0.7rem; font-weight: bold;">Lihat Semua -></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
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
        <div class="col-12 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.9rem; font-weight: bold;">
                        <i class="bi bi-lightning-charge-fill"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <a href="{{ route('tiket.create') }}" class="text-decoration-none">
                                <div class="p-3 rounded" style="background:#EBF4FF; cursor:pointer;">
                                    <i class="bi bi-plus-circle-fill" style="font-size:1.8rem; color:#4e73df;"></i>
                                    <p class="mb-0 mt-2" style="font-size:0.8rem; color:#4e73df; font-weight:600;">Buat Tiket</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-4 text-center">
                            <a href="{{ route('tiket.index') }}" class="text-decoration-none">
                                <div class="p-3 rounded" style="background:#E8F8F1; cursor:pointer;">
                                    <i class="bi bi-list-check" style="font-size:1.8rem; color:#1cc88a;"></i>
                                    <p class="mb-0 mt-2" style="font-size:0.8rem; color:#1cc88a; font-weight:600;">Semua Tiket</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-4 text-center">
                            <a href="{{ route('tiket.history') }}" class="text-decoration-none">
                                <div class="p-3 rounded" style="background:#FEF9EC; cursor:pointer;">
                                    <i class="bi bi-clock-history" style="font-size:1.8rem; color:#f6c23e;"></i>
                                    <p class="mb-0 mt-2" style="font-size:0.8rem; color:#f6c23e; font-weight:600;">Riwayat Tiket</p>
                                </div>
                            </a>
                        </div>

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

<!-- modal alert email -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="profileModalLabel" style="color: white;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Lengkapi Profil Anda
                </h5>
            </div>

            <div class="modal-body">
                <p class="mb-2">
                    Alamat email Anda belum diisi.
                </p>

                <p class="mb-0">
                    Silakan lengkapi alamat email pada menu <strong>Profil</strong> agar Anda dapat menerima notifikasi terkait Tiket Anda.
                </p>
            </div>

            <div class="modal-footer">
                <a href="{{ route('profile.index') }}" class="btn btn-primary">
                    <i class="fas fa-user-edit me-1"></i>
                    Lengkapi Profil
                </a>
            </div>

        </div>
    </div>
</div>
@push('scripts')



@php
$showProfileModal = auth()->check() && empty(auth()->user()->email);
@endphp
<script>
    $(function() {

        let showProfileModal = @json($showProfileModal);

        if (showProfileModal) {
            $('#profileModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        } else {
            $('#infoModal').modal('show');
        }

    });
</script>
<script>
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


    // $(document).ready(function() {
    //     $('#infoModal').modal('show');
    // });

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
</script>
@endpush
@endsection