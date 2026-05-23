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
                    <h3>{{ $totalSales }}</h3>
                    <p>Total Sales</p>
                </div>
                <div class="icon"><i class="fas fa-receipt"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>KES {{ number_format($avgSale, 2) }}</h3>
                    <p>Average Sale</p>
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
                            <th>Sales</th>
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

        {{-- By Category --}}
        <div class="col-md-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-utensils mr-2"></i>By Category</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>Category</th>
                            <th>Sales</th>
                            <th>Revenue</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($byCategory as $category => $data)
                            <tr>
                                <td>{{ $category }}</td>
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

    {{-- All Sales --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-2"></i>All Transactions</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover table-sm" id="sales-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Staff</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
                        <td>{{ $sale->staff->name ?? '—' }}</td>
                        <td>{{ $sale->item->name ?? '—' }}</td>
                        <td>{{ $sale->item->category ?? '—' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>KES {{ number_format($sale->unit_price, 2) }}</td>
                        <td>KES {{ number_format($sale->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No sales found for this period.</td>
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
            $('#sales-table').DataTable({
                order: [[1, 'desc']],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [0] }]
            });
        });
    </script>
@stop
