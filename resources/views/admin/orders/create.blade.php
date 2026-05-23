@extends('adminlte::page')

@section('title', 'Create Order')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4 class="m-0 text-dark">Create Order</h4>
            <ol class="breadcrumb p-0 m-0 bg-transparent">
                <li class="breadcrumb-item active">Home</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                </li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times mr-1"></i> Cancel
            </a>
        </div>
    </div>
@stop

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}">Orders</a>
    </li>
    <li class="breadcrumb-item active">Create Order</li>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i>Order Details</h3>
                </div>

                <form action="{{ route('admin.orders.store') }}" method="POST">
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

                        {{-- Payment Method --}}
                        <div class="form-group">
                            <label for="payment_method_id">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method_id" id="payment_method_id"
                                    class="form-control select2 @error('payment_method_id') is-invalid @enderror">
                                <option value="">— Select Payment Method —</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Table Number --}}
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="table_number">Table Number</label>
                                    <input type="text" name="table_number" id="table_number"
                                           class="form-control @error('table_number') is-invalid @enderror"
                                           value="{{ old('table_number') }}" maxlength="50" placeholder="e.g. T-01">
                                    @error('table_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Order Date --}}
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="order_date">Order Date <span class="text-danger">*</span></label>
                                    <input type="date" name="order_date" id="order_date"
                                           class="form-control @error('order_date') is-invalid @enderror"
                                           value="{{ old('order_date', now()->toDateString()) }}">
                                    @error('order_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Order Items --}}
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="m-0">Order Items</h5>
                            <button type="button" class="btn btn-success btn-sm" id="add-item">
                                <i class="fas fa-plus mr-1"></i> Add Item
                            </button>
                        </div>

                        @error('items')
                        <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror

                        <div id="items-container">
                            {{-- Rows injected here by JS, one rendered on load --}}
                        </div>

                        {{-- Order Total Preview --}}
                        <div class="d-flex justify-content-end mt-3">
                            <div class="text-right">
                                <small class="text-muted d-block">Order Total</small>
                                <span class="h4 font-weight-bold text-success" id="order_total">0.00</span>
                            </div>
                        </div>

                    </div>{{-- /.card-body --}}

                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Create Order
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

            // Build item prices map from Blade data
            const prices = {
                @foreach($items as $item)
                    {{ $item->id }}: {{ $item->price }},
                @endforeach
            };

            // Item options HTML (reused when adding rows)
            const itemOptions = `<option value="">— Select Item —</option>`
                + {!! json_encode($items->map(fn($i) => "<option value=\"{$i->id}\" data-price=\"{$i->price}\">[{$i->code}] {$i->name}</option>")->implode('')) !!};

            let rowIndex = 0;

            function makeRow(index) {
                // Restore old values on validation failure
                const oldItemId  = {{ json_encode(old('items')) }} ? ({{ json_encode(old('items')) }}[index] || {})['item_id']  || '' : '';
                const oldQty     = {{ json_encode(old('items')) }} ? ({{ json_encode(old('items')) }}[index] || {})['quantity'] || 1  : 1;

                return `
                <div class="item-row border rounded p-3 mb-2 bg-light" data-index="${index}">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="small text-muted mb-1">Item <span class="text-danger">*</span></label>
                                <select name="items[${index}][item_id]"
                                        class="form-control select2-item @error('items.${index}.item_id') is-invalid @enderror"
                                        data-index="${index}">
                                    ${itemOptions}
                                </select>
                                @error('items.${index}.item_id')
                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <label class="small text-muted mb-1">Qty <span class="text-danger">*</span></label>
                    <input type="number" name="items[${index}][quantity]"
                                       class="form-control item-qty"
                                       value="${oldQty}" min="1" placeholder="1">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label class="small text-muted mb-1">Unit Price</label>
                                <input type="text" class="form-control bg-white item-price" value="0.00" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label class="small text-muted mb-1">Line Total</label>
                                <input type="text" class="form-control bg-white font-weight-bold text-success item-total" value="0.00" readonly>
                            </div>
                        </div>
                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row mt-3" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
            }

            function addRow() {
                const $row = $(makeRow(rowIndex));
                $('#items-container').append($row);

                // Init Select2 on the new item dropdown
                $row.find('.select2-item').select2({ width: '100%' });

                rowIndex++;
                updateOrderTotal();
            }

            function updateOrderTotal() {
                let total = 0;
                $('.item-row').each(function () {
                    total += parseFloat($(this).find('.item-total').val()) || 0;
                });
                $('#order_total').text(total.toFixed(2));
            }

            // Item selected → fill unit price & recalc line
            $(document).on('change', '.select2-item', function () {
                const $row   = $(this).closest('.item-row');
                const itemId = $(this).val();
                const price  = itemId && prices[itemId] !== undefined ? prices[itemId] : 0;
                $row.find('.item-price').val(price.toFixed(2));
                recalcRow($row);
            });

            // Qty changed → recalc line
            $(document).on('input change', '.item-qty', function () {
                recalcRow($(this).closest('.item-row'));
            });

            function recalcRow($row) {
                const price = parseFloat($row.find('.item-price').val()) || 0;
                const qty   = parseInt($row.find('.item-qty').val())     || 0;
                $row.find('.item-total').val((price * qty).toFixed(2));
                updateOrderTotal();
            }

            // Remove row (keep at least one)
            $(document).on('click', '.remove-row', function () {
                if ($('.item-row').length > 1) {
                    $(this).closest('.item-row').remove();
                    updateOrderTotal();
                } else {
                    toastr.warning('An order must have at least one item.');
                }
            });

            // Add item button
            $('#add-item').on('click', addRow);

            // Initialise all other Select2 fields (business, staff, payment method)
            $('.select2').select2({ width: '100%' });

            // Start with one empty row
            addRow();
        });
    </script>
@stop
