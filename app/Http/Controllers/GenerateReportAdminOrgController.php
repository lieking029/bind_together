<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\View;

class GenerateReportAdminOrgController extends Controller
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
        $activityType = $request->input('activity_type');

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

            if ($activityType) {
                $query->whereHas('activity', function ($q) use ($activityType) {
                    $q->whereIn('activity_type', $activityType);
                });
            }

            if ($sports && count($sports) > 0) {
                $query->whereHas('activity', function ($q) use ($sports) {
                    $q->whereIn('sport_id', $sports);
                });
            }

            if ($status) {
                $query->whereIn('status', $status);
            }

            if ($yearLevel && count($yearLevel) > 0) {
                $query->whereHas('user', function ($q) use ($yearLevel) {
                    $q->whereIn('year_level', $yearLevel);
                });
            }

            $results = $query->get();

            // Check for file type
            if ($fileType === 'pdf') {
                return $this->generatePDF($results, $startDate, $endDate, $reportType);
            } elseif ($fileType === 'docx') {
                return $this->generateActivityDocx($results, $startDate, $endDate);
            }
        } elseif ($reportType == 3) {
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
                return $this->generatePDF($results, $startDate, $endDate, $reportType);
            }
            if ($fileType === 'docx') {
                return $this->generateActivityDocx($results, $startDate, $endDate);
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

    protected function generateDocx($results, $startDate, $endDate)
    {
        $phpWord = new PhpWord();

        // Set default font and size for the document
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        // Add a section with landscape orientation
        $section = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginTop' => 400,
            'marginBottom' => 400,
            'marginLeft' => 600,
            'marginRight' => 600,
        ]);

        // Add the university logo and header information with proper alignment
        $header = $section->addHeader();
        $header->addImage(public_path('images/bindtogether-logo.png'), [
            'width' => 100,
            'height' => 100,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
            'wrappingStyle' => 'inline'
        ]);

        $header->addText('Bataan Peninsula State University', ['bold' => true, 'size' => 16, 'color' => '800000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $header->addText('Bind Together', ['italic' => true, 'size' => 12, 'color' => '800000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $header->addText('City of Balanga, 2100 Bataan', ['italic' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $header->addText('Tel: (047) 237-3309 | www.bpsu.edu.ph | Email: bpsu.bindtogether@gmail.com', ['italic' => true, 'size' => 10], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $section->addTextBreak(1);
        $section->addText("Activities Report for $startDate - $endDate", ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $section->addTextBreak(1);

        // Add table with proper cell formatting and 75% width
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'width' => 100 * 75,  // 75% width
        ];
        $phpWord->addTableStyle('activityTable', $tableStyle);

        $table = $section->addTable('activityTable');
        $table->addRow();
        $table->addCell(2000)->addText('User', ['bold' => true]);
        $table->addCell(2000)->addText('Sport', ['bold' => true]);
        $table->addCell(2000)->addText('Height', ['bold' => true]);
        $table->addCell(2000)->addText('Weight', ['bold' => true]);
        $table->addCell(2000)->addText('Date Registered', ['bold' => true]);

        // Loop through the registrations and add rows to the table
        foreach ($results as $registration) {
            $table->addRow();
            $table->addCell(2000)->addText($registration->user->firstname . ' ' . $registration->user->lastname);
            $table->addCell(2000)->addText($registration->activity->sport->name);
            $table->addCell(2000)->addText($registration->height);
            $table->addCell(2000)->addText($registration->weight);
            $table->addCell(2000)->addText($registration->created_at->format('Y-m-d'));
        }

        // Add footer for signature with right alignment
        $footer = $section->addFooter();
        $footer->addText('Reported by:', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $footer->addText('(Space for signature)', ['italic' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);
        $footer->addText('(name of the report generator)', ['italic' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT]);

        // Generate and return the Word document
        $fileName = 'activities_report_' . now()->format('Y_m_d') . '.docx';
        $tempFile = storage_path($fileName);
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    protected function generateActivityDocx($results, $startDate, $endDate)
    {
        $query = Activity::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with('user')->get();


        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText("Bataan Peninsula State University", ['bold' => true, 'size' => 16, 'color' => '800000']);
        $section->addText("Bind Together", ['bold' => true, 'size' => 14, 'color' => '800000']);
        $section->addText("Activities Report for $startDate - $endDate", ['bold' => true, 'size' => 12]);
        $section->addTextBreak(1);

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText("Title", ['bold' => true]);
        $table->addCell(2000)->addText("Type", ['bold' => true]);
        $table->addCell(2000)->addText("Venue", ['bold' => true]);
        $table->addCell(2000)->addText("Target Audience", ['bold' => true]);
        $table->addCell(2000)->addText("Activity Duration", ['bold' => true]);

        foreach ($results as $activity) {
            $table->addRow();
            $table->addCell(2000)->addText($activity->title);
            $table->addCell(2000)->addText($activity->type);
            $table->addCell(2000)->addText($activity->venue);
            $table->addCell(2000)->addText($activity->target_player);
            $table->addCell(2000)->addText("{$activity->start_date} to {$activity->end_date}");
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'activity_report_') . '.docx';
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    /*    protected function generateCoachesPDF($fileType)
    {
        $coaches = Coach::all(); // Fetch all coaches, or you can apply filters based on request parameters.

        // Load the selected view with the data
        $pdf = Pdf::loadView('coach.reports.list-report', [
            'coaches' => $coaches,
        ])->setPaper('a4', 'landscape');

        // Stream the PDF file with the current date in the filename
        return $pdf->stream('coaches_report_' . now()->format('Y_m_d') . '.pdf');
    } */
}
