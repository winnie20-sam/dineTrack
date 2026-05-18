@extends('adminlte::page')

@section('title', 'Sales')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Sales Records</h1>
        <a href="{{ route('admin.sales.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Record Sale
        </a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">All Sales</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Business</th>
                    <th>Staff</th>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                    <th>Recorded By</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        <td>{{ $sale->business->name ?? '—' }}</td>
                        <td>{{ $sale->staff->name ?? '—' }}</td>
                        <td>
                            <span class="badge badge-light border">{{ $sale->item->code ?? '' }}</span>
                            {{ $sale->item->name ?? '—' }}
                        </td>
                        <td class="text-center">{{ $sale->quantity }}</td>
                        <td class="text-right">{{ number_format($sale->unit_price, 2) }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($sale->total, 2) }}</td>
                        <td>{{ $sale->createdBy->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                            No sales recorded yet.
                        </td>
                    </tr>
                @endforelse
                </tbody>
                @if($sales->count())
                    <tfoot class="thead-light">
                    <tr>
                        <th colspan="7" class="text-right">Grand Total</th>
                        <th class="text-right text-success font-weight-bold">
                            {{ number_format($sales->sum('total'), 2) }}
                        </th>
                        <th></th>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)

@section('js')
    <script>
        $(function () {
            $('table').DataTable({
                order: [[1, 'desc']],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [8] }]
            });
        });
    </script>
@stop
