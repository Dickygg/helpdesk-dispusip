@extends('_layouts.app')

@section('title', 'Edit Role User')
@section('page-title', 'Edit Role User')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            Edit Role — {{ $user->name }}
        </h6>
        <a href="{{ route('manage.user-roles.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-4">
            <i class="fas fa-user mr-1"></i>
            <strong>{{ $user->name }}</strong>
            &mdash; {{ $user->username }}
        </div>

        <form action="{{ route('manage.user-roles.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="font-weight-bold">
                    Pilih Role <span class="text-danger">*</span>
                </label>
                @error('roles')
                <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror
                <div class="row mt-2">
                    @foreach($roles as $role)
                    <div class="col-md-3 mb-2">
                        <div class="card shadow-sm {{ in_array($role->name, $userRoles) ? 'border-primary' : '' }}">
                            <div class="card-body py-2 px-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="custom-control-input"
                                        name="roles[]"
                                        value="{{ $role->name }}"
                                        id="role_{{ $role->id }}"
                                        {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold"
                                        for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <hr>
            <a href="{{ route('manage.user-roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Role
            </button>
        </form>
    </div>
</div>
@endsection