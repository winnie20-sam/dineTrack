<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $label }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        h4 { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #343a40; color: white; padding: 6px; text-align: left; }
        td { padding: 5px; border-bottom: 1px solid #dee2e6; }
        .summary { margin-bottom: 20px; }
        .summary td { font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { background: #f8f9fa; font-weight: bold; }
    </style>
</head>
<body>

<h2>{{ $business->name }}</h2>
<h3 style="text-align:center;">{{ $label }}</h3>
<p style="text-align:center;">Generated on {{ now()->format('d M Y H:i') }}</p>

{{-- Summary --}}
<table class="summary">
    <tr>
        <td>Total Revenue:</td>
        <td>KES {{ number_format($totalRevenue, 2) }}</td>
        <td>Total Sales:</td>
        <td>{{ $totalSales }}</td>
        <td>Average Sale:</td>
        <td>KES {{ number_format($avgSale, 2) }}</td>
    </tr>
</table>

{{-- By Staff --}}
<h4>Sales by Staff</h4>
<table>
    <thead>
    <tr><th>Staff</th><th>Sales</th><th>Revenue</th></tr>
    </thead>
    <tbody>
    @foreach($byStaff as $name => $data)
        <tr>
            <td>{{ $name }}</td>
            <td>{{ $data['count'] }}</td>
            <td>KES {{ number_format($data['revenue'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- By Category --}}
<h4>Sales by Category</h4>
<table>
    <thead>
    <tr><th>Category</th><th>Sales</th><th>Revenue</th></tr>
    </thead>
    <tbody>
    @foreach($byCategory as $category => $data)
        <tr>
            <td>{{ $category }}</td>
            <td>{{ $data['count'] }}</td>
            <td>KES {{ number_format($data['revenue'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- All Transactions --}}
<h4>All Transactions</h4>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Date</th>
        <th>Staff</th>
        <th>Item</th>
        <th>Category</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $sale)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>
            <td>{{ $sale->staff->name ?? '—' }}</td>
            <td>{{ $sale->item->name ?? '—' }}</td>
            <td>{{ $sale->item->category ?? '—' }}</td>
            <td>{{ $sale->quantity }}</td>
            <td>KES {{ number_format($sale->unit_price, 2) }}</td>
            <td>KES {{ number_format($sale->total, 2) }}</td>
        </tr>
    @endforeach
    <tr class="total-row">
        <td colspan="7" class="text-right">Grand Total</td>
        <td>KES {{ number_format($totalRevenue, 2) }}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
