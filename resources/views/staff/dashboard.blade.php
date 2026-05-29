@extends('adminlte::page')

@section('title', 'Staff Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Welcome, {{ auth()->user()->name }}</h1>
        <span class="badge badge-primary badge-pill px-3 py-2">
            <i class="fas fa-store mr-1"></i> {{ $staff->business->name }}
        </span>
    </div>
@stop

@section('content')

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Orders</span>
                    <span class="info-box-number">{{ $todayCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Revenue</span>
                    <span class="info-box-number">KES {{ number_format($totalToday, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-bar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Orders (All Time)</span>
                    <span class="info-box-number">{{ $totalOrders }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Orders --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-2"></i>Today's Orders</h3>
            <div class="card-tools">
                <a href="{{ route('staff.orders.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> New Order
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm table-hover" id="today-orders-table">
                <thead class="thead-light">
                    <tr>
                        <th>Order No.</th>
                        <th>Items</th>
                        <th>Table</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayOrders as $order)
                        <tr>
                            <td><span class="badge badge-primary">{{ $order->order_number }}</span></td>
                            <td>{{ $order->items->count() }}</td>
                            <td>{{ $order->table_number ?? '—' }}</td>
                            <td>{{ $order->paymentMethod->name ?? '—' }}</td>
                            <td><strong>KES {{ number_format($order->total, 2) }}</strong></td>
                            <td>
                                <a href="{{ route('staff.orders.show', $order) }}" class="btn btn-xs btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                                No orders today yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Legacy sale section — kept for reference, replaced by orders --}}
    {{--
    <div class="row mb-4">
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Sales Count</span>
                    <span class="info-box-number">{{ $todayCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Today's Total</span>
                    <span class="info-box-number">KES {{ number_format($todayTotal, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-bar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Sales (All Time)</span>
                    <span class="info-box-number">{{ $totalSales }}</span>
                </div>
            </div>
        </div>
    </div>
    --}}

@stop

@section('plugins.Datatables', true)

@section('js')
    <script>
        $(function () {
            $('#today-orders-table').DataTable({
                order: [[0, 'desc']],
                pageLength: 10,
            });
        });
    </script>
@stop
