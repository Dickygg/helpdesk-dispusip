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
</style>
@endpush
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



    $(document).ready(function() {
        $('#infoModal').modal('show');
    });
</script>
@endpush
@endsection