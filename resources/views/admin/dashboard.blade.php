@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Dashboard</h1>
        <span class="text-muted">{{ now()->format('l, d M Y') }}</span>
    </div>
@stop

@section('content')

    {{-- Overall Summary Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>KES {{ number_format($totalRevenue, 2) }}</h3>
                    <p>Total Revenue Today</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalBusinesses }}</h3>
                    <p>Total Businesses</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalStaff }}</h3>
                    <p>Total Staff</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalItems }}</h3>
                    <p>Total Menu Items</p>
                </div>
                <div class="icon"><i class="fas fa-utensils"></i></div>
            </div>
        </div>
    </div>

    {{-- Per Business Breakdown --}}
    <div class="row">
        @forelse($businesses as $business)
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-building mr-2"></i>
                            {{ $business->name }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-{{ $business->status->name === 'Active' ? 'success' : 'secondary' }}">
                                {{ $business->status->name }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-money-bill-wave mr-2 text-success"></i> Revenue Today</span>
                                <strong class="text-success">KES {{ number_format($business->today_revenue, 2) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-receipt mr-2 text-info"></i> Sales Today</span>
                                <strong>{{ $business->today_sales }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-star mr-2 text-warning"></i> Top Item</span>
                                <strong>{{ $business->top_item?->item?->name ?? '—' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-shopping-cart mr-2 text-primary"></i> Total Sales</span>
                                <strong>{{ $business->sales_count }}</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.sales.index') }}?business={{ $business->id }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye mr-1"></i> View Sales
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i> No businesses found.
                    <a href="{{ route('admin.businesses.create') }}">Add your first business.</a>
                </div>
            </div>
        @endforelse
    </div>

@stop
