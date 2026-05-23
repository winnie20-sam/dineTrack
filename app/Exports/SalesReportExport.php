<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $sales;
    protected $label;
    protected $totalRevenue;
    protected $totalSales;
    protected $avgSale;

    public function __construct($sales, $label, $totalRevenue, $totalSales, $avgSale)
    {
        $this->sales        = $sales;
        $this->label        = $label;
        $this->totalRevenue = $totalRevenue;
        $this->totalSales   = $totalSales;
        $this->avgSale      = $avgSale;
    }

    public function collection()
    {
        $rows = $this->sales->map(fn($sale) => [
            'Date'       => $sale->sale_date,
            'Staff'      => $sale->staff->name ?? '—',
            'Item'       => $sale->item->name ?? '—',
            'Category'   => $sale->item->category ?? '—',
            'Quantity'   => $sale->quantity,
            'Unit Price' => $sale->unit_price,
            'Total'      => $sale->total,
        ]);

        // Add summary rows at the bottom
        $rows->push([]);
        $rows->push(['', '', '', '', '', 'Total Sales',   $this->totalSales]);
        $rows->push(['', '', '', '', '', 'Total Revenue', $this->totalRevenue]);
        $rows->push(['', '', '', '', '', 'Average Sale',  round($this->avgSale, 2)]);

        return $rows;
    }

    public function headings(): array
    {
        return ['Date', 'Staff', 'Item', 'Category', 'Quantity', 'Unit Price', 'Total'];
    }

    public function title(): string
    {
        return $this->label;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
