<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SalesReportExport;
use App\Http\Controllers\BaseController;
use App\Models\Business;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends BaseController
{
    /**
     * Display the report filter form
     */
    public function index()
    {
        $businesses = Business::all();
        return view('admin.reports.index', compact('businesses'));
    }

    /**
     * Generate and display the report
     *
     * @param Request $request
     * @return \Illuminate\View\View|RedirectResponse
     */
    public function generate(Request $request)
    {
        try {
            $valid = $this->validateReportPayload($request);
            if ($valid->fails()) {
                return redirect()->back()
                    ->withErrors($valid)
                    ->withInput();
            }

            [$business, $sales, $label, $from, $to, $totalRevenue, $totalSales, $avgSale, $byCategory, $byStaff, $byItem] = $this->buildReport($request);

            return view('admin.reports.show', compact(
                'business',
                'sales',
                'label',
                'from',
                'to',
                'totalRevenue',
                'totalSales',
                'avgSale',
                'byCategory',
                'byStaff',
                'byItem'
            ));
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Export report as PDF
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function exportPdf(Request $request)
    {
        try {
            [$business, $sales, $label, $from, $to, $totalRevenue, $totalSales, $avgSale, $byCategory, $byStaff, $byItem] = $this->buildReport($request);

            $pdf = Pdf::loadView('admin.reports.pdf', compact(
                'business',
                'sales',
                'label',
                'from',
                'to',
                'totalRevenue',
                'totalSales',
                'avgSale',
                'byCategory',
                'byStaff',
                'byItem'
            ))->setPaper('a4', 'landscape');

            return $pdf->download('dinetrack-report-' . now()->format('Y-m-d') . '.pdf');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Export report as Excel
     *
     * @param Request $request
     * @return RedirectResponse|BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        try {
            [$business, $sales, $label, $from, $to, $totalRevenue, $totalSales, $avgSale, $byCategory, $byStaff, $byItem] = $this->buildReport($request);

            return Excel::download(
                new SalesReportExport($sales, $label, $totalRevenue, $totalSales, $avgSale, $byCategory, $byStaff, $byItem),
                'dinetrack-report-' . now()->format('Y-m-d') . '.xlsx'
            );
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    //  validate report request payload
    // -------------------------------------------------------------------------

    /**
     * Payload validator for generating a report
     *
     * @param Request $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateReportPayload(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'date_from'   => 'required|date',
            'date_to'     => 'required|date|after_or_equal:date_from',
        ]);
    }

    // -------------------------------------------------------------------------
    //  build report data from request
    // -------------------------------------------------------------------------

    /**
     * Build all report data from the request
     *
     * @param Request $request
     * @return array
     */
    private function buildReport(Request $request): array
    {
        $business = Business::findOrFail($request->business_id);
        $from     = Carbon::parse($request->date_from)->startOfDay();
        $to       = Carbon::parse($request->date_to)->endOfDay();
        $label    = ucfirst($request->type) . ' Report — ' . $from->format('d M Y') . ' to ' . $to->format('d M Y');

        $sales = Sale::with(['staff', 'item'])
            ->where('business_id', $business->id)
            ->whereBetween('sale_date', [$from, $to])
            ->latest()
            ->get();

        $totalRevenue = $sales->sum('total');
        $totalSales   = $sales->count();
        $avgSale      = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        $byCategory = $this->groupSales($sales, 'item.category');
        $byStaff    = $this->groupSales($sales, 'staff.name')->sortByDesc('revenue');
        $byItem     = $this->groupSales($sales, 'item.name')->sortByDesc('revenue');

        return [
            $business, $sales, $label, $from, $to,
            $totalRevenue, $totalSales, $avgSale,
            $byCategory, $byStaff, $byItem,
        ];
    }

    /**
     * Group sales collection by a given key
     *
     * @param Collection $sales
     * @param string $key
     * @return Collection
     */
    private function groupSales($sales, string $key): Collection
    {
        return $sales->groupBy($key)->map(fn($group) => [
            'count'   => $group->count(),
            'revenue' => $group->sum('total'),
        ]);
    }
}
