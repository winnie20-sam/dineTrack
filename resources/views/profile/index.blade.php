@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <h1 class="m-0 text-dark">My Profile</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user mr-2"></i>Profile Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control" value="{{ $user->role->name ?? '—' }}" readonly>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Update Profile
                            </button>
                            <a href="{{ route('profile.password.page') }}" class="btn btn-warning">
                                <i class="fas fa-lock mr-1"></i> Change Password
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
