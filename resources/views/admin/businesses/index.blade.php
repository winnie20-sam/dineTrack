@extends('adminlte::page')

@section('title', 'Businesses')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Businesses</h1>

        <a href="{{ route('admin.businesses.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i>
            Add Business
        </a>
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
            <h3 class="card-title">All Businesses</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm"
                   id="businesses-table">

                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @forelse($businesses as $business)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $business->name }}</td>

                        <td>{{ $business->email ?? '—' }}</td>

                        <td>{{ $business->phone ?? '—' }}</td>

                        <td>
                            <span class="badge badge-{{ $business->status->name === 'Active' ? 'success' : 'secondary' }}">
                                {{ $business->status->name ?? '—' }}
                            </span>
                        </td>

                        <td>{{ $business->createdBy->name ?? '—' }}</td>

                        <td>
                            <a href="{{ route('admin.businesses.edit', $business) }}"
                               class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.businesses.destroy', $business) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Remove this business?')">
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
                        <td colspan="7"
                            class="text-center text-muted py-4">
                            <i class="fas fa-store fa-2x mb-2 d-block"></i>
                            No businesses yet.
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
            $('#businesses-table').DataTable({
                order: [[0, 'desc']],
                pageLength: 25,
                columnDefs: [
                    { orderable: false, targets: [6] }
                ]
            });
        });
    </script>
@stop
