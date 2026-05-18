@extends('adminlte::page')

@section('title', 'Record Sale')

@section('content_header')
    <h1 class="m-0 text-dark">Record a Sale</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">

        {{-- Sale Form --}}
        <div class="col-lg-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-plus mr-2"></i>New Sale</h3>
                </div>
                <form action="{{ route('staff.sale.store') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label>Item <span class="text-danger">*</span></label>
                            <select name="item_id" id="item_id"
                                    class="form-control select2 @error('item_id') is-invalid @enderror" required>
                                <option value="">— Select Item —</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                        [{{ $item->code }}] {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', 1) }}" min="1" required>
                            @error('quantity')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Unit Price <span class="text-danger">*</span></label>
                            <input type="number" name="unit_price" id="unit_price"
                                   class="form-control @error('unit_price') is-invalid @enderror"
                                   step="0.01" min="0" placeholder="0.00" required>
                            @error('unit_price')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Total</label>
                            <input type="text" id="total_preview"
                                   class="form-control bg-light font-weight-bold text-success text-right"
                                   value="0.00" readonly>
                        </div>

                        <div class="form-group">
                            <label>Sale Date <span class="text-danger">*</span></label>
                            <input type="date" name="sale_date" id="sale_date"
                                   class="form-control @error('sale_date') is-invalid @enderror"
                                   value="{{ old('sale_date', now()->toDateString()) }}" required>
                            @error('sale_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Record Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Today's Sales --}}
        <div class="col-lg-8">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Today's Sales</h3>
                    <div class="card-tools">
                        <span class="badge badge-success px-3 py-2">
                            Total: KES {{ number_format($todayTotal, 2) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <table id="sales-table" class="table table-sm table-striped mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($todaySales as $i => $sale)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $sale->item->name ?? '—' }}</td>
                                <td class="text-center">{{ $sale->quantity }}</td>
                                <td class="text-right">{{ number_format($sale->unit_price, 2) }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($sale->total, 2) }}</td>
                                <td>{{ $sale->sale_date }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No sales today.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@stop

@section('plugins.Select2', true)
@section('plugins.Datatables', true)

@section('js')
    <script>
        $(function () {
            $('.select2').select2({ width: '100%' });

            $('#sales-table').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                language: { search: 'Filter:' }
            });

            const prices = {
                @foreach($items as $item)
                    {{ $item->id }}: {{ $item->price }},
                @endforeach
            };

            function recalc() {
                const qty   = parseFloat($('#quantity').val())   || 0;
                const price = parseFloat($('#unit_price').val()) || 0;
                $('#total_preview').val((qty * price).toFixed(2));
            }

            $('#item_id').on('change', function () {
                const price = prices[$(this).val()];
                if (price !== undefined) $('#unit_price').val(price);
                else $('#unit_price').val('');
                recalc();
            });

            $('#quantity, #unit_price').on('input change', recalc);
            recalc();
        });
    </script>
@stop
