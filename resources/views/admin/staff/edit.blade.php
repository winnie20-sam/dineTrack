@extends('adminlte::page')

@section('title', 'Edit Staff')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">Edit Staff Member</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.staff.index') }}">Staff</a>
                </li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times mr-1"></i> Cancel
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Staff Details</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="business_id">Business <span class="text-danger">*</span></label>
                            <select name="business_id" id="business_id"
                                    class="form-control select2 @error('business_id') is-invalid @enderror">
                                <option value="">-- Select Business --</option>
                                @foreach($businesses as $business)
                                    <option value="{{ $business->id }}"
                                        {{ old('business_id', $staff->business_id) == $business->id ? 'selected' : '' }}>
                                        {{ $business->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $staff->name) }}">
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $staff->phone) }}">
                            @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status_id">Status <span class="text-danger">*</span></label>
                            <select name="status_id" id="status_id"
                                    class="form-control select2 @error('status_id') is-invalid @enderror">
                                <option value="">-- Select Status --</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('status_id', $staff->status_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end px-0 pb-0">
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('plugins.Select2', true)

@section('js')
    <script>
        $(function () {
            $('.select2').select2({ width: '100%' });
        });
    </script>
@stop
