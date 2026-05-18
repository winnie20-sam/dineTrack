@extends('adminlte::page')

@section('title', 'Edit Business')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit Business</h1>
        <a href="{{ route('admin.businesses.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.businesses.update', $business) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $business->name) }}">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $business->email) }}">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $business->phone) }}">
                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status_id" class="form-control @error('status_id') is-invalid @enderror">
                        <option value="">-- Select Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('status_id', $business->status_id) == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Business
                </button>
            </form>
        </div>
    </div>
@endsection
