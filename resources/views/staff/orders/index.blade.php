@extends('adminlte::page')

@section('title', 'My Orders')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">My Orders</h1>
        <a href="{{ route('staff.orders.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> New Order
        </a>
    </div>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm" id="orders-table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Order No.</th>
                        <th>Items</th>
                        <th>Table</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge badge-primary">{{ $order->order_number }}</span></td>
                            <td>{{ $order->items->count() }}</td>
                            <td>{{ $order->table_number ?? '—' }}</td>
                            <td>{{ $order->paymentMethod->name ?? '—' }}</td>
                            <td><strong>KES {{ number_format($order->total, 2) }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status->name === 'Active' ? 'success' : 'secondary' }}">
                                    {{ $order->status->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('staff.orders.show', $order) }}" class="btn btn-xs btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('plugins.Datatables', true)

@section('js')
    <script>
        $(function () {
            $('#orders-table').DataTable({
                order: [[0, 'desc']],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [8] }]
            });
        });
    </script>
@stop
