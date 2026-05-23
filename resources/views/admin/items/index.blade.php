@extends('adminlte::page')

@section('title', 'Menu Items')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">Menu Items</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Menu Items</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Item
            </a>
        </div>
    </div>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            {!! session('success') !!}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">All Menu Items</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm" id="items-table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Business</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span class="badge badge-info">{{ $item->code }}</span>
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category ?? '—' }}</td>
                        <td>KES {{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->business->name ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $item->status->name === 'Active' ? 'success' : 'secondary' }}">
                                {{ $item->status->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.items.destroy', $item) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Remove this menu item?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-xs btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-utensils fa-2x mb-2 d-block"></i>
                            No menu items yet.
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
            $('#items-table').DataTable({
                order: [[0, 'desc']],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [7] }]
            });
        });
    </script>
@stop
