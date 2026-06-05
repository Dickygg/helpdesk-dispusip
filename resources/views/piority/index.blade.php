@extends('_layouts.app')

@section('title', 'Daftar Tingkat Prioritas')
@section('page-title', 'Daftar Tingkat Prioritas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <a href="" data-toggle="modal" data-target="#printModal" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>
            <a href="" data-toggle="modal" data-target="#excelModal" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Excel</a>

        </div>

    </div>
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary ">Dispusip<span class="text-info">Helpdesk.</span></h6>
            <a href="" class="btn btn-primary" data-toggle="modal" data-target="#pioritybaruModal" class="btn btn-primary btn-sm float-right">Tambah Data Tingkat Prioritas</a>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tingkat Prioritas</th>
                            <th>Estimasi Perkerjaan (JAM)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$r->name}}</td>
                            <td>{{$r->estimated_hours}} Jam</td>
                            <td class="text-center">
                                <div class="gap-1">
                                    <a href="{{ route('piority.edit', $r->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('piority.destroy', $r->id) }}" method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
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
<div class="modal fade" id="pioritybaruModal" tabindex="-1"
    role="dialog" aria-labelledby="pioritybaruModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="pioritybaruModalLabel">Tambah Daftar Tingkat Prioritas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('piority.store')}}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" value="{{ @old('name') }}" id="name" name="name"
                            placeholder="Masukan Nama Tingkat Prioritas">
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control form-control-user @error('description') is-invalid @enderror" value="{{ @old('estimated_hours') }}" id="estimated_hours" name="estimated_hours"
                            placeholder="Masukan Estimasi Perkerjaan (JAM)">
                        @error('estimated_hours')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah</button>
                </div>
            </form>
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
@endpush
@endsection