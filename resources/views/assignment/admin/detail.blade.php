@extends('_layouts.app')
@section('title', 'Detail Assignment')
@section('page-title', 'Detail Assignment')
@section('content')
@push('styles')
<style>
    .log-hidden {
        display: none;
    }

    .icon-ticket {
        margin-right: 8px;
        font-size: 1.5rem;
        width: 42px;
        height: 42px;
        padding: 0;
        color: #3b82f6;
        background-color: #d3e5fdff;
        border-radius: 35%;
    }

    .colums-card-body {
        border-right: 1px solid #dee2e6;
    }

    @media (max-width:767.98px) {
        .colums-card-body {
            border-right: 0px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 10px;
            margin-top: 10px;
        }
    }
</style>


@endpush
@php

$priorityStyle = match($data->ticket->priority?->name) {
'Emergency' => 'text-danger',
'Urgent' => 'text-warning',
'Normal' => 'text-success',
default => 'text-secondary',
};
$nodepriorityStyle = match($data->ticket->priority?->name) {
'Emergency' => 'bg-danger',
'Urgent' => 'bg-warning',
'Normal' => 'bg-success',
default => 'bg-secondary',
};

$verificationStyle = match($data->ticket->verification_status) {
'pending' => 'text-warning',
'verified' => 'text-success',
'rejected' => 'text-danger',
default => 'text-secondary',
};

$statusStyle = match($data->ticket->status){
'Open' => 'btn-primary',
'Accept' => 'btn-info',
'Assigned' => 'btn-warning',
'In Progress' => 'btn-warning',
'Resolved' => 'btn-success',
'Closed' => 'btn-secondary',
'Rejected' => 'btn-danger',
'Reopen' => 'btn-danger',
default => 'btn-secondary',
};
@endphp

@php
$prefix = match(true) {
auth()->user()->hasRole('super admin') => 'sa.admin.',
auth()->user()->hasRole('admin helpdesk') => 'admin.',
default => ''
};
@endphp
<div id="section-print">
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary ">Dispusip<span class="text-info">Helpdesk.</span></h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header bg-transparent">
                                <div class="row">
                                    <div class="col-md-6 d-flex align-items-center">
                                        <i class="icon-ticket bi bi-ticket-detailed rounded-5 d-md-flex align-items-center justify-content-center d-none"></i>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark" style="font-size: 0.95rem; letter-spacing: 0.3px; font-weight:bold;">{{$data->ticket['ticket_code']}}</span>
                                            <span style="font-size: 0.65rem; letter-spacing: 0.3px; font-weight:bold;">Dibuat Pada: {{$data->ticket->created_at->format('d M Y')}}</span>
                                        </div>
                                    </div>
                                    <div class="col d-flex justify-content-end" style="height: fit-content;">
                                        <a href="{{route($prefix.'assigment.history')}}" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row d-flex" style="margin-bottom:0;">
                                    <div class="col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-card-text"></i> Judul Tiket</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;">{{$data->ticket['title']}}</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-menu-app"></i> Aplikasi</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"></i> {{$data->ticket->application->name}}</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-bookmark-star"></i> Prioritas</div>
                                        <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                            <span class="{{$nodepriorityStyle}}" style="margin-right:5px;width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                                            <span class="{{ $priorityStyle }} fw-bold">{{$data->ticket->priority?->name ?? 'Belum Ditentukan'}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-shield-check"></i> Verifikasi Data</div>
                                        <div class="text-{{$verificationStyle}} text-capitalize" style="font-size: 0.75rem; font-weight: bold;">{{$data->ticket->verification_status}}</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row d-flex" style="margin-bottom:0;">
                                    <div class="col-md-03 col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-collection"></i> Tipe Tiket</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"> {{ $data->ticket->tickettype?->name ?? 'Belum Ditentukan' }}</div>
                                    </div>
                                    <div class="col-md-03 col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-clock-history"></i> Terakhir Di Update</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"> {{$data->ticket->updated_at? $data->ticket->updated_at->format('d F Y, H:i') : '-'}}</div>
                                    </div>
                                    <div class="col-md-03 col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-person-gear"></i> Diasign Oleh</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;">
                                            {{ $data->admin?->name}}
                                        </div>
                                    </div>
                                    <div class="col-md-03 col-md-3 col-sm-6 d-flex justify-content-center">
                                        <i class="btn btn-sm rounded-5 {{$statusStyle}}" style="margin-right: 4px; cursor:default; height:fit-content;">{{$data->ticket['status']}}</i>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col">
                                        <div class="d-flex flex-column">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="text-primary" style="font-size: 0.85rem; font-weight: bold; margin-bottom:6px;"><i class="bi bi-card-text"></i> Deskripsi Tiket</div>
                                                    <div class="p-3 bg-light rounded">
                                                        <p class="mb-0">{{ $data->ticket->description ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="text-primary" style="font-size: 0.85rem; font-weight: bold; margin-bottom:6px;"><i class="bi bi-card-text"></i>
                                                    @if($data->ticket?->status == 'Reopen' || $data->ticket?->status == 'Rejected')
                                                    Alasaan Penolakan
                                                    @else
                                                    Catatan Pengerjaan
                                                    @endif
                                                </div>
                                                @if($data->ticket->status == 'Rejected'|| $data->ticket->status == 'Reopen')
                                                <div class="p-3 bg-danger text-light rounded mt-2">
                                                    <p class="mb-0">{{ $data->ticket->reason_rejected ?? '-' }}</p>
                                                </div>

                                                @elseif($data->ticket->status == 'Resolved' || $data->ticket->status == 'Closed')
                                                <div class="p-3  bg-light text-secondary rounded mt-2">
                                                    <p class="mb-0">{{ $data->ticket->note ?? '-' }}</p>
                                                    @if($data->Assignattachments?->file_path)
                                                    <a href="{{ Storage::url($data->Assignattachments->file_path) }}" target="_blank">
                                                        <span class="btn btn-sm btn-light text-success mt-2">
                                                            <i class="fas fa-eye"></i> Bukti Pengerjaan
                                                        </span>
                                                    </a>
                                                    @endif
                                                </div>
                                                @else
                                                <div class="p-3  bg-light rounded mt-2">
                                                    <p class="mb-0">{{ $data->ticket->note ?? '-' }}</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card mb-3 border-0 shadow-sm rounded-4">
                                    <div class="card-header bg-white border-bottom rounded-top-4">
                                        <span class="text-primary" style="font-size: 0.85rem; letter-spacing: 0.3px; font-weight:bold;">
                                            <i class="bi bi-lightning" style="margin-right: 5px;"></i>Quick Info
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        {{-- Info --}}
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted" style="font-size: 0.78rem; font-weight: bold;">Deadline</span>
                                                <span class="text-danger fw-bold" style="font-size: 0.78rem; font-weight: bold;">
                                                    {{ $data->ticket->due_date ? $data->ticket->due_date->format('d F Y, H:i') : '-' }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted" style="font-size: 0.78rem; font-weight: bold;">Ditugaskan Oleh</span>
                                                <span class="text-dark fw-bold" style="font-size: 0.78rem; font-weight: bold;">{{$data->admin?->name}}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted" style="font-size: 0.78rem; font-weight: bold;">Waktu Pengerjaan</span>
                                                <span class="text-dark fw-bold" style="font-size: 0.78rem; font-weight: bold">
                                                    @php
                                                    $menit = $data->work_duration ?? 0;
                                                    $jam = intdiv($menit, 60);
                                                    $sisa = $menit % 60;
                                                    @endphp
                                                    @if($data->work_duration)
                                                    {{ $jam > 0 ? $jam . ' jam ' : '' }}{{ $sisa > 0 ? $sisa . ' menit' : '' }}
                                                    @else
                                                    -
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted" style="font-size: 0.78rem; font-weight: bold;">Pengguna Konfrimasi</span>
                                                <span class="text-dark fw-bold" style="font-size: 0.78rem; font-weight: bold">
                                                    @if(!$data->ticket?->user_confirmed_at)
                                                    Belum Dikonfirmasi
                                                    @else
                                                    Pengguna Sudah Konfirmasi
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-danger" style="font-size: 0.78rem; font-weight: bold;">Tiket akan otomatis diKonfirmasi dalam 3 hari jika tidak ada Aktivitas dari Pemilik Tiket.</span>
                                            </div>
                                        </div>

                                        <hr class="my-2">

                                        {{-- Action Buttons --}}
                                        <div class="d-flex flex-column gap-2">
                                            <a onclick="downloadPDF()" class="mb-2 d-flex justify-content-center align-items-center btn btn-outline-success btn-sm">
                                                <i class="bi bi-download" style="margin-right: 5px;"></i> Unduh PDF
                                            </a>
                                            @forelse ($data->ticket->attachments as $attachment)
                                            <a href="{{asset('storage/' . $attachment->file_path . '/' . $attachment->file_name) }}" target="_blank" class="mb-2 d-flex justify-content-center align-items-center btn btn-outline-info btn-sm">
                                                <i class="bi bi-eye" style="margin-right: 5px;"></i> Lihat Lampiran
                                            </a>
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <span class="text-primary" style="font-size: 0.85rem; letter-spacing: 0.3px; font-weight:bold;">
                                            <i class="fas fa-history"></i> Riwayat Aktivitas
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        @forelse($logs as $log)
                                        <div class="log-item {{ $loop->index >= 3 ? 'log-hidden' : '' }}">

                                            <div class="d-flex mb-3">
                                                {{-- Avatar --}}
                                                <div class="mr-3">
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                        style="width:35px; height:35px; font-size:11px;">
                                                        {{ strtoupper(substr($log->causer?->name ?? 'S', 0, 2)) }}
                                                    </div>
                                                </div>

                                                {{-- Detail --}}
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>{{ $log->causer?->name ?? 'System' }}</strong>
                                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                    </div>

                                                    <p class="mb-1">{{ $log->description }}</p>

                                                    @if($log->properties->isNotEmpty())
                                                    @if($log->properties->has('before'))
                                                    <small class="text-muted d-block">
                                                        <strong>Sebelum:</strong>
                                                        @foreach($log->properties['before'] as $key => $value)
                                                        <span class="badge badge-light">{{ $key }}: {{ $value ?? '-' }}</span>
                                                        @endforeach
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <strong>Sesudah:</strong>
                                                        @foreach($log->properties['after'] as $key => $value)
                                                        <span class="badge badge-light">{{ $key }}: {{ $value ?? '-' }}</span>
                                                        @endforeach
                                                    </small>
                                                    @elseif($log->properties->has('dari'))
                                                    <small class="text-muted">
                                                        <span class="badge badge-warning">{{ $log->properties['dari'] }}</span>
                                                        <i class="fas fa-arrow-right mx-1"></i>
                                                        <span class="badge badge-success">{{ $log->properties['ke'] }}</span>
                                                    </small>
                                                    @else
                                                    @foreach($log->properties as $key => $value)
                                                    <small class="text-muted">
                                                        <span class="badge badge-light">{{ $key }}: {{ $value }}</span>
                                                    </small>
                                                    @endforeach
                                                    @endif
                                                    @endif
                                                </div>
                                            </div>

                                            @if(!$loop->last)
                                            <hr class="my-2">
                                            @endif

                                        </div>
                                        @empty
                                        <p class="text-muted text-center mb-0">Belum ada aktivitas.</p>
                                        @endforelse

                                        {{-- Tombol Selengkapnya --}}
                                        @if($logs->count() > 3)
                                        <div class="text-center mt-2">
                                            <button class="btn btn-sm btn-outline-primary" id="btnSelengkapnya" onclick="toggleLogs(this)">
                                                <i class="fas fa-chevron-down mr-1"></i>
                                                Selengkapnya ({{ $logs->count() - 3 }} lainnya)
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    const ticketCode = "{{ $data->ticket->ticket_code }}";
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
        pdf.save(`Detail_assignment_${ticketCode}.pdf`);
    }

    function toggleLogs(btn) {
        const hiddenLogs = document.querySelectorAll('.log-hidden, .log-item.log-visible-extra');

        const isHidden = document.querySelector('.log-item:nth-child(4)') &&
            document.querySelector('.log-item:nth-child(4)').style.display === 'none' ||
            document.querySelector('.log-hidden') !== null;

        document.querySelectorAll('.log-item').forEach((el, index) => {
            if (index >= 3) {
                if (el.classList.contains('log-hidden')) {
                    el.classList.remove('log-hidden');
                    btn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i> Sembunyikan';
                    btn.classList.replace('btn-outline-primary', 'btn-outline-secondary');
                } else {
                    el.classList.add('log-hidden');
                    btn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i> Selengkapnya ({{ $logs->count() - 3 }} lainnya)';
                    btn.classList.replace('btn-outline-secondary', 'btn-outline-primary');
                }
            }
        });
    }
</script>
@endpush
@endsection