@extends('adminlte::page')

@section('title', 'Staff')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">Staff Members</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item active">Staff</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Add Staff
            </a>
        </div>
    </div>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {!! session('success') !!}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">All Staff</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm" id="staff-table">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Business</th>
                    <th>Status</th>
                    <th>Login</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($staff as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email ?? '—' }}</td>
                        <td>{{ $member->phone ?? '—' }}</td>
                        <td>{{ $member->business->name ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $member->status->name === 'Active' ? 'success' : 'secondary' }}">
                                {{ $member->status->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @if($member->user)
                                <span class="badge badge-info"><i class="fas fa-check mr-1"></i>Has Login</span>
                            @else
                                <span class="badge badge-warning">No Login</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.staff.edit', $member) }}" class="btn btn-xs btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.staff.destroy', $member) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Remove this staff member and their login?')">
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
                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                            No staff members yet.
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
            $('#staff-table').DataTable({
                order: [[0, 'desc']],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [7] }]
            });
        });
    </script>
@stop
