@extends('adminlte::page')

@section('title', 'New Order')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">New Order</h1>
        <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
    </div>
@stop

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <form action="{{ route('staff.orders.store') }}" method="POST" id="order-form">
        @csrf

        <div class="row">

            {{-- Left — Order Details --}}
            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Order Details</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method_id" class="form-control @error('payment_method_id') is-invalid @enderror">
                                <option value="">— Select Payment —</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Table Number <small class="text-muted">(optional)</small></label>
                            <input type="text" name="table_number" class="form-control"
                                   value="{{ old('table_number') }}" placeholder="e.g. Table 5">
                        </div>

                        <div class="form-group">
                            <label>Order Date <span class="text-danger">*</span></label>
                            <input type="date" name="order_date" class="form-control"
                                   value="{{ old('order_date', now()->toDateString()) }}">
                            @error('order_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- Grand Total --}}
                        <div class="form-group">
                            <label>Grand Total</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">KES</span>
                                </div>
                                <input type="text" id="grand-total-display" class="form-control font-weight-bold text-success bg-light" readonly value="0.00">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right — Order Items --}}
            <div class="col-md-8">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-utensils mr-2"></i>Order Items</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" id="add-item">
                                <i class="fas fa-plus mr-1"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Item</th>
                                    <th width="80">Qty</th>
                                    <th width="120">Unit Price</th>
                                    <th width="120">Total</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                <tr id="empty-row">
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-utensils fa-2x mb-2 d-block"></i>
                                        Click "Add Item" to start adding items
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="3" class="text-right font-weight-bold">Grand Total</td>
                                    <td class="font-weight-bold text-success" id="grand-total">KES 0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>

@stop

@section('plugins.Select2', true)

@section('js')
<script>
    const items = @json($items);
    let rowIndex = 0;

    function buildItemOptions() {
        let options = '<option value="">— Select Item —</option>';
        items.forEach(item => {
            options += `<option value="${item.id}" data-price="${item.price}">[${item.code}] ${item.name} — KES ${parseFloat(item.price).toFixed(2)}</option>`;
        });
        return options;
    }

    function addRow() {
        $('#empty-row').hide();
        const index = rowIndex++;
        const row = `
            <tr id="row-${index}">
                <td>
                    <select name="items[${index}][item_id]" class="form-control form-control-sm item-select" id="item-select-${index}">
                        ${buildItemOptions()}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][quantity]"
                           class="form-control form-control-sm qty-input" value="1" min="1">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm unit-price" readonly placeholder="0.00">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm row-total" readonly placeholder="0.00">
                </td>
                <td>
                    <button type="button" class="btn btn-xs btn-danger remove-row" data-row="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        $('#items-body').append(row);
        $(`#item-select-${index}`).select2({
            width: '100%',
            placeholder: '— Select Item —',
        });
    }

    function calcRow(row) {
        const price = parseFloat($(row).find('.item-select option:selected').data('price')) || 0;
        const qty   = parseFloat($(row).find('.qty-input').val()) || 0;
        const total = price * qty;
        $(row).find('.unit-price').val(price.toFixed(2));
        $(row).find('.row-total').val(total.toFixed(2));
        calcGrandTotal();
    }

    function calcGrandTotal() {
        let grand = 0;
        $('.row-total').each(function () {
            grand += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').text('KES ' + grand.toFixed(2));
        $('#grand-total-display').val(grand.toFixed(2));
    }

    $(function () {
        addRow();

        $('#add-item').on('click', addRow);

        $(document).on('change', '.item-select', function () {
            calcRow($(this).closest('tr'));
        });

        $(document).on('input', '.qty-input', function () {
            calcRow($(this).closest('tr'));
        });

        $(document).on('click', '.remove-row', function () {
            const index = $(this).data('row');
            $(`#row-${index}`).remove();
            calcGrandTotal();
            if ($('#items-body tr:visible').length === 0) {
                $('#empty-row').show();
            }
        });
    });
</script>
@stop
