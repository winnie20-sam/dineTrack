@extends('adminlte::page')

@section('title', 'Edit Staff')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Staff</h1>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Business</label>
                    <select name="business_id" class="form-control @error('business_id') is-invalid @enderror">
                        <option value="">-- Select Business --</option>
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}" {{ old('business_id', $staff->business_id) == $business->id ? 'selected' : '' }}>
                                {{ $business->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $staff->name) }}">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $staff->phone) }}">
                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status_id" class="form-control @error('status_id') is-invalid @enderror">
                        <option value="">-- Select Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('status_id', $staff->status_id) == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Staff
                </button>
            </form>
        </div>
    </div>
@endsection
