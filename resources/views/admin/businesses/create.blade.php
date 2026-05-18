@extends('adminlte::page')

@section('title', 'Add Business')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Add Business</h1>

        <a href="{{ route('admin.businesses.index') }}"
           class="btn btn-default btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>
            Please fix the errors below.
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Business Details</h3>
        </div>

        <form action="{{ route('admin.businesses.store') }}" method="POST">
            @csrf

            <div class="card-body">

                <div class="form-group">
                    <label for="name">Business Name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Restaurant name">

                    @error('name')
                    <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="Business email">

                    @error('email')
                    <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}"
                           placeholder="Phone number">

                    @error('phone')
                    <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save mr-1"></i>
                    Save Business
                </button>

                <a href="{{ route('admin.businesses.index') }}"
                   class="btn btn-secondary btn-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@stop
