<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityRegistration;
use App\Models\Practice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportCoachController extends Controller
{
    public function index()
    {
        return view('coach.reports.index');
    }

    public function generateReport(Request $request)
    {
        $reportType = $request->input('type');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $yearLevel = $request->input('year_level');
        $fileType = $request->input('file_type');

        if ($reportType == 1 || $reportType == 2) {
            $query = ActivityRegistration::query();

            if ($reportType == 1) {
                $query->where('status', 0);
            } elseif ($reportType == 2) {
                $query->where('status', 1);
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($yearLevel) {
                // Ensure that $yearLevel is always treated as an array
                if (!is_array($yearLevel)) {
                    $yearLevel = [$yearLevel];
                }

                if (count($yearLevel) > 0) {
                    $query->whereHas('user', function ($q) use ($yearLevel) {
                        $q->whereIn('year_level', $yearLevel);
                    });
                }
            }

            $results = $query->get();
            // $results->load('user', 'sport');

            if ($fileType == 'pdf') {
                return $this->generatePDF($results, $startDate, $endDate, $reportType); // Pass reportType to the generatePDF method
            }
        } elseif ($reportType == 3) {
            $query = Practice::query();

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($yearLevel) {
                if (!is_array($yearLevel)) {
                    $yearLevel = [$yearLevel];
                }

                if (count($yearLevel) > 0) {
                    $query->whereHas('user', function ($q) use ($yearLevel) {
                        $q->whereIn('year_level', $yearLevel);
                    });
                }
            }

            $results = $query->get();
            $results->load('user', 'activity.sport');

            if ($fileType == 'pdf') {
                return $this->generatePDF($results, $startDate, $endDate, $reportType); // Pass reportType to the generatePDF method
            }
        } else {
            $query = Activity::query();
            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($yearLevel) {
                if (!is_array($yearLevel)) {
                    $yearLevel = [$yearLevel];
                }

                if (count($yearLevel) > 0) {
                    $query->whereHas('user', function ($q) use ($yearLevel) {
                        $q->whereIn('year_level', $yearLevel);
                    });
                }
            }

            $results = $query->get();
            $results->load('user');

            if ($fileType == 'pdf') {
                return $this->generatePDF($results, $startDate, $endDate, $reportType); // Pass reportType to the generatePDF method
            }
        }
    }

    protected function generatePDF($results, $startDate, $endDate, $reportType)
    {
        switch ($reportType) {
            case 1:
                $view = 'coach.reports.tryout-reports';
                break;
            case 2:
                $view = 'coach.reports.official-player-report';
                break;
            case 3:
                $view = 'coach.reports.practice-report';
                break;
            case 4:
                $view = 'coach.reports.activity-report';
                break;
                // default:
                //     $view = 'coach.reports.default-report'; // Add a default case if necessary
                //     break;
        }

        // Load the selected view with the data
        $pdf = Pdf::loadView($view, [
            'registrations' => $results,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4', 'landscape');

        // Stream the PDF file with the current date in the filename
        return $pdf->stream('activities_report_' . now()->format('Y_m_d') . '.pdf');
    }
}
