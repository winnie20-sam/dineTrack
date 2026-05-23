@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">Orders</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> New Order
            </a>
        </div>
    </div>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Orders</li>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
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
                    <th>Business</th>
                    <th>Staff</th>
                    <th>Table</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="badge badge-primary">{{ $order->order_number }}</span></td>
                        <td>{{ $order->business->name ?? '—' }}</td>
                        <td>{{ $order->staff->name ?? '—' }}</td>
                        <td>{{ $order->table_number ?? '—' }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td><strong>KES {{ number_format($order->total, 2) }}</strong></td>
                        <td>{{ $order->paymentMethod->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status->name === 'Active' ? 'success' : 'secondary' }}">
                                {{ $order->status->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-xs btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Cancel this order?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-danger">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
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
                columnDefs: [{ orderable: false, targets: [10] }]
            });
        });
    </script>
@stop
