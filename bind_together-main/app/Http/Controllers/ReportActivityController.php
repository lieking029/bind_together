<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportActivityController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $reportType = $request->input('type');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $yearLevel = $request->input('year_level');
        $sports = $request->input('sports');
        $fileType = $request->input('file_type');

        $query = Activity::query();

        if ($reportType == 1) {
            $query->where('status', 0);
        } elseif ($reportType == 2) {
            $query->where('status', 1);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // if ($sports && count($sports) > 0) {
        //     $query->whereHas('activity', function ($q) use ($sports) {
        //         $q->whereIn('sport_id', $sports);
        //     });
        // }

        if ($status) {
            $query->whereIn('status', $status);
        }

        // if ($yearLevel && count($yearLevel) > 0 ) {
        //     $query->whereHas('user', function ($q) use ($yearLevel) {
        //         $q->whereIn('year_level', $yearLevel);
        //     });
        // }

        $results = $query->get();

        // Check for file type
        if ($fileType === 'pdf') {
            return $this->generatePDF($results, $startDate, $endDate);
        } elseif ($fileType === 'docx') {
            return $this->generateDocx($results, $startDate, $endDate);
        }

        return view('reports.docx', compact('results'));
    }

    protected function generatePDF($results, $startDate, $endDate)
    {
        $pdf = Pdf::loadView('reports.activity-docs', [
            'registrations' => $results,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('activities_report_' . now()->format('Y_m_d') . '.pdf');
    }


}
