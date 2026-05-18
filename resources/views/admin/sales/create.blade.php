@extends('adminlte::page')

@section('title', 'Record Sale')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Record a Sale</h1>
        <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Sales
        </a>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-receipt mr-2"></i>Sale Details</h3>
                </div>

                <form action="{{ route('admin.sales.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        {{-- Business --}}
                        <div class="form-group">
                            <label for="business_id">Business <span class="text-danger">*</span></label>
                            <select name="business_id" id="business_id"
                                    class="form-control select2 @error('business_id') is-invalid @enderror">
                                <option value="">— Select Business —</option>
                                @foreach($businesses as $business)
                                    <option value="{{ $business->id }}"
                                        {{ old('business_id') == $business->id ? 'selected' : '' }}>
                                        {{ $business->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('business_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Staff --}}
                        <div class="form-group">
                            <label for="staff_id">Staff Member <span class="text-danger">*</span></label>
                            <select name="staff_id" id="staff_id"
                                    class="form-control select2 @error('staff_id') is-invalid @enderror">
                                <option value="">— Select Staff —</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}"
                                        {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Item --}}
                        <div class="form-group">
                            <label for="item_id">Item <span class="text-danger">*</span></label>
                            <select name="item_id" id="item_id"
                                    class="form-control select2 @error('item_id') is-invalid @enderror">
                                <option value="">— Select Item —</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}"
                                            data-price="{{ $item->price }}"
                                        {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        [{{ $item->code }}] {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Quantity --}}
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           value="{{ old('quantity', 1) }}" min="1" placeholder="1">
                                    @error('quantity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Unit Price --}}
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="unit_price">
                                        Unit Price <span class="text-danger">*</span>
                                        <small class="text-muted">(auto-filled, editable)</small>
                                    </label>
                                    <input type="number" name="unit_price" id="unit_price"
                                           class="form-control @error('unit_price') is-invalid @enderror"
                                           value="{{ old('unit_price') }}" min="0" step="0.01" placeholder="0.00">
                                    @error('unit_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Total (read-only preview) --}}
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Total</label>
                                    <div class="input-group">
                                        <input type="text" id="total_preview"
                                               class="form-control bg-light font-weight-bold text-success"
                                               value="0.00" readonly>
                                    </div>
                                    <small class="text-muted">Calculated automatically</small>
                                </div>
                            </div>
                        </div>

                        {{-- Sale Date --}}
                        <div class="form-group">
                            <label for="sale_date">Sale Date <span class="text-danger">*</span></label>
                            <input type="date" name="sale_date" id="sale_date"
                                   class="form-control @error('sale_date') is-invalid @enderror"
                                   value="{{ old('sale_date', now()->toDateString()) }}">
                            @error('sale_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>{{-- /.card-body --}}

                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Record Sale
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@stop

@section('plugins.Select2', true)

@section('js')
    <script>
        $(function () {

            // Initialise Select2
            $('.select2').select2({ width: '100%' });

            // Item prices map built from blade data
            const prices = {
                @foreach($items as $item)
                    {{ $item->id }}: {{ $item->price }},
                @endforeach
            };

            function recalcTotal() {
                const qty   = parseFloat($('#quantity').val())   || 0;
                const price = parseFloat($('#unit_price').val()) || 0;
                $('#total_preview').val((qty * price).toFixed(2));
            }

            // Auto-fill unit price when item is selected
            $('#item_id').on('change', function () {
                const itemId = $(this).val();
                if (itemId && prices[itemId] !== undefined) {
                    $('#unit_price').val(prices[itemId]);
                } else {
                    $('#unit_price').val('');
                }
                recalcTotal();
            });

            // Recalculate whenever qty or price changes
            $('#quantity, #unit_price').on('input change', recalcTotal);

            // Trigger on page load if old values are present (validation failure redirection)
            recalcTotal();
        });
    </script>
@stop
