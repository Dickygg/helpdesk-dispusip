@extends('_layouts.app')
@section('title', 'Detail Tiket')
@section('page-title', 'Detail Tiket')
@section('content')
@push('styles')
<style>
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
$priorityStyle = match($tiket->priority?->name) {
'High' => 'text-danger',
'Medium' => 'text-warning',
'Low' => 'text-success',
default => 'text-secondary',
};

$verificationStyle = match($tiket->verification_status) {
'pending' => 'text-warning',
'verified' => 'text-success',
'rejected' => 'text-danger',
default => 'text-secondary',
};

$statusStyle = match($tiket->status){
'Open' => 'btn-primary',
'Accept' => 'btn-info',
'Assigned' => 'btn-warning',
'In Progres' => 'btn-warning',
'Resolved' => 'btn-success',
'Closed' => 'btn-secondary',
'Rejected' => 'btn-danger',
default => 'btn-secondary',
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
                                    <div class="col-md-8 d-flex align-items-center">
                                        <i class="icon-ticket bi bi-ticket-detailed rounded-5 d-md-flex align-items-center justify-content-center d-none"></i>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark" style="font-size: 0.95rem; letter-spacing: 0.3px; font-weight:bold;">{{$tiket['ticket_code']}}</span>
                                            <span style="font-size: 0.65rem; letter-spacing: 0.3px; font-weight:bold;">Dibuat Pada: {{$tiket->created_at->format('d M Y')}}</span>
                                        </div>
                                    </div>
                                    <div class="col d-flex justify-content-md-end" style="height: fit-content;">
                                        <a onclick="downloadPDF()" class="btn btn-outline-primary btn-sm"><i class="bi bi-download"></i> Unduh PDF</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row d-flex" style="margin-bottom:0;">
                                    <div class="col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-card-text"></i> Judul Tiket</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;">{{$tiket['title']}}</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-menu-app"></i> Aplikasi</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"></i> {{$tiket['application']->name}}</div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-bookmark-star"></i> Prioritas</div>
                                        <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                            <span style="margin-right:5px;width: 8px; height: 8px; border-radius: 50%; background-color: #726f6fff; display: inline-block;"></span>
                                            <span class="{{ $priorityStyle }} fw-bold">{{$tiket->priority?->name ?? 'Belum Ditentukan'}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-shield-check"></i> Verifikasi Data</div>
                                        <div class="{{$verificationStyle}} text-capitalize" style="font-size: 0.75rem; font-weight: bold;">{{$tiket['verification_status']}}</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row d-flex" style="margin-bottom:0;">
                                    <div class="col-md-03 col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-alarm"></i> Estimasi Selesai</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"> {{$tiket->due_date ? \Carbon\Carbon::parse($tiket->due_date)->format('d M Y'): '-'}}</div>
                                    </div>
                                    <div class="col-md-03 col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-clock-history"></i> Terakhir Di Update</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;"> {{$tiket['updated_at']}}</div>
                                    </div>
                                    <div class="col-md-03 col-md-3 col-sm-6 colums-card-body">
                                        <div class="text-secondary" style="font-size: 0.85rem; font-weight: bold;"><i class="bi bi-person-gear"></i> PIC Petugas</div>
                                        <div class="text-dark" style="font-size: 0.75rem; font-weight: bold;">
                                            @if(!$tiket->assignment?->technician?->name)
                                            Belum Ditentukan.
                                            @else
                                            {{ $tiket->assignment?->technician?->name ?? '-' }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-03 col-md-3 col-sm-6 d-flex justify-content-center">
                                        <i class="btn btn-sm rounded-5 {{$statusStyle}}" style="margin-right: 4px; cursor:default; height:fit-content;">{{$tiket['status']}}</i>
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
                                                        <p class="mb-0">{{ $tiket->description ?? '-' }}</p>
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
                                                <div class="text-primary" style="font-size: 0.85rem; font-weight: bold; margin-bottom:6px;"><i class="bi bi-card-text"></i> Catatan Pengerjaan</div>
                                                @if($tiket->status == 'Rejected')
                                                <div class="p-3 bg-danger text-light rounded mt-2">
                                                    <p class="mb-0">{{ $tiket->note ?? '-' }}</p>
                                                </div>

                                                @elseif($tiket->status == 'Resolved' || $tiket->status == 'Closed')
                                                <div class="p-3 bg-success text-light rounded mt-2">
                                                    <p class="mb-0">{{ $tiket->description ?? '-' }}</p>
                                                    <a href="#" class=""> <span class="btn btn-sm btn-light text-success mt-4"><i class="fas fa-eye"></i> Bukti pengerjaan</span></a>
                                                </div>
                                                @else
                                                <div class="p-3 bg-dispusip rounded mt-2">
                                                    <p class="mb-0">{{ $tiket->note ?? '-' }}</p>
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
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <span class="fw-bold ">Attachments Tiket</span>
                                    </div>
                                    <div class="card-body">
                                        @forelse ($tiket->attachments as $attachment)
                                        <img src="{{ asset('storage/' . $attachment->file_path . '/' . $attachment->file_name) }}"
                                            alt="{{ $attachment->file_name }}"
                                            class="img-fluid rounded mb-2">
                                        @empty
                                        <p class="text-muted">Tidak ada lampiran.</p>
                                        @endforelse
                                    </div>
                                    <div class="card-footer d-flex justify-content-between text-muted small">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">
                                            <i class="fas fa-history"></i> Riwayat Aktivitas
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @forelse($logs as $log)
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

                                                {{-- Deskripsi --}}
                                                <p class="mb-1">{{ $log->description }}</p>

                                                {{-- Properties --}}
                                                @if($log->properties->isNotEmpty())
                                                @if($log->properties->has('before'))
                                                {{-- Tampilkan before & after --}}
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
                                                {{-- Tampilkan perubahan status --}}
                                                <small class="text-muted">
                                                    <span class="badge badge-warning">{{ $log->properties['dari'] }}</span>
                                                    <i class="fas fa-arrow-right mx-1"></i>
                                                    <span class="badge badge-success">{{ $log->properties['ke'] }}</span>
                                                </small>
                                                @else
                                                {{-- Tampilkan properties lainnya --}}
                                                @foreach($log->properties as $key => $value)
                                                <small class="text-muted">
                                                    <span class="badge badge-light">{{ $key }}: {{ $value }}</span>
                                                </small>
                                                @endforeach
                                                @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Garis pemisah --}}
                                        @if(!$loop->last)
                                        <hr class="my-2">
                                        @endif

                                        @empty
                                        <p class="text-muted text-center mb-0">Belum ada aktivitas.</p>
                                        @endforelse
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
    const ticketCode = "{{ $tiket->ticket_code }}";
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
        pdf.save(`Detail_Tiket_${ticketCode}.pdf`);
    }
</script>
@endpush
@endsection