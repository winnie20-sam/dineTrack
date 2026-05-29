@extends('adminlte::page')

@section('title', 'Report')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">{{ $label }}</h1>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <a href="{{ route('admin.reports.export.pdf', request()->all()) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </a>
            <a href="{{ route('admin.reports.export.excel', request()->all()) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
        </div>
    </div>
@stop

@section('content')

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>KES {{ number_format($totalRevenue, 2) }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalOrders }}</h3>
                    <p>Total Orders</p>
                </div>
                <div class="icon"><i class="fas fa-receipt"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>KES {{ number_format($avgOrder, 2) }}</h3>
                    <p>Average Order</p>
                </div>
                <div class="icon"><i class="fas fa-chart-line"></i></div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- By Staff --}}
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-2"></i>By Staff</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byStaff as $name => $data)
                                <tr>
                                    <td>{{ $name }}</td>
                                    <td>{{ $data['count'] }}</td>
                                    <td>KES {{ number_format($data['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- By Payment Method --}}
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-credit-card mr-2"></i>By Payment Method</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Payment</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byPayment as $payment => $data)
                                <tr>
                                    <td>{{ $payment }}</td>
                                    <td>{{ $data['count'] }}</td>
                                    <td>KES {{ number_format($data['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- By Item --}}
        <div class="col-md-4">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-star mr-2"></i>Top Items</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byItem as $name => $data)
                                <tr>
                                    <td>{{ $name }}</td>
                                    <td>{{ $data['count'] }}</td>
                                    <td>KES {{ number_format($data['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- All Orders --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-2"></i>All Orders</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover table-sm" id="orders-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                        <th>Date</th>
                        <th>Staff</th>
                        <th>Table</th>
                        <th>Items</th>
                        <th>Payment</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge badge-primary">{{ $order->order_number }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                            <td>{{ $order->staff->name ?? '—' }}</td>
                            <td>{{ $order->table_number ?? '—' }}</td>
                            <td>{{ $order->items->count() }}</td>
                            <td>{{ $order->paymentMethod->name ?? '—' }}</td>
                            <td>KES {{ number_format($order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No orders found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold bg-light">
                        <td colspan="7" class="text-right">Grand Total</td>
                        <td>KES {{ number_format($totalRevenue, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@stop

@section('plugins.Datatables', true)

@section('js')
    <script>
        $(function () {
            $('#orders-table').DataTable({
                order: [[2, 'desc']],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [0] }]
            });
        });
    </script>
@stop
