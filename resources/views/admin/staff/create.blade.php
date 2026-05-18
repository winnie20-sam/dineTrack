@extends('adminlte::page')

@section('title', 'Add Staff Member')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Add Staff Member</h1>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Staff
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Staff Details</h3>
                </div>

                <form action="{{ route('admin.staff.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label for="business_id">Business <span class="text-danger">*</span></label>
                            <select name="business_id" id="business_id"
                                    class="form-control select2 @error('business_id') is-invalid @enderror">
                                <option value="">— Select Business —</option>
                                @foreach($businesses as $business)
                                    <option value="{{ $business->id }}"
                                        {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                        {{ $business->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Jane Doe">
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="e.g. jane@example.com">
                            <small class="text-muted">This will be used as their login email.</small>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" placeholder="+254 700 000 000">
                            @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="callout callout-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            A temporary password will be generated and shown after saving.
                            The staff member should change it on first login.
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Add Staff Member
                        </button>
                    </div>
                </form>
            </div>
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
