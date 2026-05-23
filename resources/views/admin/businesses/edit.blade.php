@extends('adminlte::page')

@section('title', 'Edit Business')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">Edit Business</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.businesses.index') }}">Businesses</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.businesses.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times mr-1"></i> Cancel
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Business Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.businesses.update', $business) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Business Name</label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $business->name) }}">
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $business->email) }}">
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $business->phone) }}">
                    @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status_id">Status</label>
                    <select name="status_id" id="status_id"
                            class="form-control @error('status_id') is-invalid @enderror">
                        <option value="">-- Select Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}"
                                {{ old('status_id', $business->status_id) == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.businesses.index') }}" class="btn btn-secondary btn-sm mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save mr-1"></i> Update Business
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
