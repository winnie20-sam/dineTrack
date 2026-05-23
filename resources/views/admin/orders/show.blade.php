@extends('adminlte::page')

@section('title', 'Order Details')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">{{ $order->order_number }}</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                </li>
                <li class="breadcrumb-item active">View Order</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print mr-1"></i> Print Receipt
            </button>
        </div>
    </div>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}">Orders</a>
    </li>
    <li class="breadcrumb-item active">{{ $order->order_number }}</li>
@stop

@section('content')
    <div class="row">

        {{-- Order Info --}}
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Order Info</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Order Number</span>
                            <strong><span class="badge badge-primary">{{ $order->order_number }}</span></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Business</span>
                            <strong>{{ $order->business->name ?? '—' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Staff</span>
                            <strong>{{ $order->staff->name ?? '—' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Table</span>
                            <strong>{{ $order->table_number ?? '—' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Payment</span>
                            <strong>{{ $order->paymentMethod->name ?? '—' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Date</span>
                            <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Status</span>
                            <span class="badge badge-{{ $order->status->name === 'Active' ? 'success' : 'secondary' }}">
                                {{ $order->status->name ?? '—' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong class="text-success">KES {{ number_format($order->total, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="col-md-8">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-utensils mr-2"></i>Order Items</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item->name ?? '—' }}</td>
                                <td>{{ $item->item->category ?? '—' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>KES {{ number_format($item->unit_price, 2) }}</td>
                                <td>KES {{ number_format($item->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No items found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="5" class="text-right">Grand Total</td>
                            <td>KES {{ number_format($order->total, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop
