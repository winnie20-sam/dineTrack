@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1 class="m-0 text-dark">Reports</h1>
@stop

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Generate Report</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.generate') }}" method="GET">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Business</label>
                            <select name="business_id" class="form-control select2">
                                <option value="">-- Select Business --</option>
                                @foreach($businesses as $business)
                                    <option value="{{ $business->id }}" {{ request('business_id') == $business->id ? 'selected' : '' }}>
                                        {{ $business->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from', now()->toDateString()) }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to', now()->toDateString()) }}">
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-chart-bar mr-1"></i> Generate Report
                </button>

            </form>
        </div>
    </div>
@stop

@section('plugins.Select2', true)
