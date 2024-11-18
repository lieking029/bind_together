<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use Carbon\Carbon;
use App\Enums\UserTypeEnum;
use Illuminate\Support\Facades\Auth;

class GenerateReportAdminSportController extends Controller
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
            $query->where('type', $activityType);

            if ($reportType == 1) {
                $query->where('status', 0); // Registered Participants
            } elseif ($reportType == 2) {
                $query->where('status', 1); // Official Participants
            }

            if($activityType == 3) {
            }

            // Apply additional filters if present
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

            // Retrieve the filtered results
            $results = $query->get();
            /*   log::info($results); */


            // Generate the report based on file type
            if ($fileType === 'pdf') {
                return $reportType == 1
                    ? $this->generateRegisteredParticipantPDF($results, $startDate, $endDate)
                    : $this->generateOfficialParticipantPDF($results, $startDate, $endDate);
            } elseif ($fileType === 'docx') {
                return $reportType == 1
                    ? $this->generateRegisteredParticipantDocx($results, $startDate, $endDate)
                    : $this->generateOfficialParticipantDocx($results, $startDate, $endDate);
            } elseif ($fileType === 'excel') {
                return $reportType == 1
                    ? $this->generateRegisteredParticipantExcel($results, $startDate, $endDate)
                    : $this->generateOfficialParticipantExcel($results, $startDate, $endDate);
            }
        }
        if ($reportType == 3) {
            $query = Activity::query();
            $query->where('type', $activityType);

            if ($activityType == 3) {
                $query->where('user_id', Auth::id());
            }

            if($activityType == 1){

            }

           

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
            if ($fileType === 'excel') {
                return $this->generateActivityExcel($results, $startDate, $endDate);
            }
        } elseif ($reportType == 4) {
            $query = ActivityRegistration::query();


            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($sports && count($sports) > 0) {
                $query->whereHas('activity', function ($q) use ($sports) {
                    $q->whereIn('sport_id', $sports);
                });
            }

            $results = $query->get();
            $results->load('user');

            Log::info($results);

            if ($fileType == 'pdf') {
                return $this->generateCoachesPDF($results, $startDate, $endDate);
            }
            if ($fileType === 'docx') {
                return $this->generateCoachesDocx($results, $startDate, $endDate);
            } elseif ($fileType === 'excel') {
                return $this->generateCoachesExcel($results, $startDate, $endDate);
            }
        }
    }
    /* Activitiy PDF */
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

    /* Activity List Excel */

    /* Ewan */
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


    /* Activity docs */
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

        // Helper function to get activity type
        function getActivityType($type)
        {
            switch ($type) {
                case \App\Enums\ActivityType::Audition:
                    return 'Audition';
                case \App\Enums\ActivityType::Tryout:
                    return 'Tryout';
                case \App\Enums\ActivityType::Practice:
                    return 'Practice';
                case \App\Enums\ActivityType::Competition:
                    return 'Competition';
                default:
                    return 'Unknown';
            }
        }

        // Helper function to get target player
        function getTargetPlayer($target)
        {
            return $target == 1 ? 'Official Player' : 'All Student';
        }


        foreach ($results as $activity) {
            $table->addRow();
            $table->addCell(2000)->addText($activity->title);
            $table->addCell(2000)->addText(getActivityType($activity->type));
            $table->addCell(2000)->addText($activity->venue);
            $table->addCell(2000)->addText(getTargetPlayer($activity->target_player));
            $table->addCell(2000)->addText("{$activity->start_date} to {$activity->end_date}");
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'activity_report_') . '.docx';
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
    /* Activity Excel */
    protected function generateActivityExcel($results, $startDate, $endDate)
    {
        $query = Activity::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with('user')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setTitle('Activities Report');
        $sheet->setCellValue('A1', 'Bataan Peninsula State University')
            ->setCellValue('A2', 'Bind Together')
            ->setCellValue('A3', "Activities Report for $startDate - $endDate");


        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('800000');
        $sheet->getStyle('A3')->getFont()->setSize(12);


        $sheet->setCellValue('A5', 'Title')
            ->setCellValue('B5', 'Type')
            ->setCellValue('C5', 'Venue')
            ->setCellValue('D5', 'Target Audience')
            ->setCellValue('E5', 'Activity Duration');


        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
        function getActivityTypeAct($type)
        {
            switch ($type) {
                case \App\Enums\ActivityType::Audition:
                    return 'Audition';
                case \App\Enums\ActivityType::Tryout:
                    return 'Tryout';
                case \App\Enums\ActivityType::Practice:
                    return 'Practice';
                case \App\Enums\ActivityType::Competition:
                    return 'Competition';
                default:
                    return 'Unknown';
            }
        }

        function getTargetPlayerAct($target)
        {
            return $target == 1 ? 'Official Player' : 'All Student';
        }
        $row = 6;
        foreach ($results as $activity) {
            $sheet->setCellValue("A$row", $activity->title);
            $sheet->setCellValue("B$row", getActivityTypeAct($activity->type));
            $sheet->setCellValue("C$row", $activity->venue);
            $sheet->setCellValue("D$row", getTargetPlayerAct($activity->target_player));
            $sheet->setCellValue("E$row", "{$activity->start_date} to {$activity->end_date}");
            $row++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        $tempFile = tempnam(sys_get_temp_dir(), 'activity_report_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }


    /* Registered Particiant PDF */
    protected function generateRegisteredParticipantPDF($results, $startDate, $endDate)
    {
        $query = ActivityRegistration::query();
        $results = $query->with('user')->get();

        $pdf = Pdf::loadView('admin-sport.reports.register_participant-report', [
            'registered_participants' => $results,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4', 'landscape');
        log::info($results);

        return $pdf->stream('registered-participant-report' . now()->format('Y_m_d') . '.pdf');
    }

    /* Official Player PDF */
    protected function generateOfficialParticipantPDF($results, $startDate, $endDate)
    {
        $query = ActivityRegistration::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with('user')->get();

        $pdf = Pdf::loadView('admin-sport.reports.official_player-report', [
            'official_players' => $results,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('official-player-report' . now()->format('Y_m_d') . '.pdf');
    }

    /* Registered Participants Docx */
    protected function generateRegisteredParticipantDocx($results, $startDate, $endDate)
    {
        $query = ActivityRegistration::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with('user')->get();


        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'orientation' => 'landscape'
        ]);

        $section->addText("Bataan Peninsula State University", ['bold' => true, 'size' => 16, 'color' => '800000']);
        $section->addText("Bind Together", ['bold' => true, 'size' => 14, 'color' => '800000']);
        $section->addText("Registered Participants Report for $startDate - $endDate", ['bold' => true, 'size' => 12]);
        $section->addTextBreak(1);

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(1500)->addText("Name", ['bold' => true]);
        $table->addCell(1500)->addText("Year Level", ['bold' => true]);
        $table->addCell(1500)->addText("Campus", ['bold' => true]);
        $table->addCell(1500)->addText("Email", ['bold' => true]); // This was previously missing in the loop
        $table->addCell(1500)->addText("Height", ['bold' => true]);
        $table->addCell(1500)->addText("Weight", ['bold' => true]);
        $table->addCell(1500)->addText("Person to Contact", ['bold' => true]);
        $table->addCell(1500)->addText("Emergency Contact", ['bold' => true]); // Corrected typo

        foreach ($results as $registered_participant) {
            $table->addRow();
            $table->addCell(1500)->addText($registered_participant->user->firstname . ' ' . $registered_participant->user->lastname);
            $table->addCell(1500)->addText($registered_participant->user->year_level);
            $table->addCell(1500)->addText($registered_participant->user->campus);
            $table->addCell(1500)->addText($registered_participant->user->email);
            $table->addCell(1500)->addText($registered_participant->height);
            $table->addCell(1500)->addText($registered_participant->weight);
            $table->addCell(1500)->addText($registered_participant->emergency_contact);
            $table->addCell(1500)->addText($registered_participant->user->contact);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'registered_participant_report_') . '.docx';
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }


    /* Official Players Docx */
    protected function generateOfficialParticipantDocx($results, $startDate, $endDate)
    {
        $query = ActivityRegistration::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with(['user.sport', 'user.campus'])->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Log::info($results);

        $section->addText("Bataan Peninsula State University", ['bold' => true, 'size' => 16, 'color' => '800000']);
        $section->addText("Bind Together", ['bold' => true, 'size' => 14, 'color' => '800000']);
        $section->addText("Official Players Report for $startDate - $endDate", ['bold' => true, 'size' => 12]);
        $section->addTextBreak(1);

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText("Student Number", ['bold' => true]);
        $table->addCell(2000)->addText("Name", ['bold' => true]);
        $table->addCell(2000)->addText("Year Level", ['bold' => true]);
        $table->addCell(2000)->addText("Campus", ['bold' => true]);
        $table->addCell(2000)->addText("Sports NAME", ['bold' => true]);
        $table->addCell(2000)->addText("Coach", ['bold' => true]);
        $table->addCell(2000)->addText("Date Registered", ['bold' => true]);

        foreach ($results as $official_player) {
            $table->addRow();
            $table->addCell(2000)->addText($official_player->user->student_number ?? 'N/A');
            $table->addCell(2000)->addText(($official_player->user->firstname ?? '') . ' ' . ($official_player->user->lastname ?? ''));
            $table->addCell(2000)->addText($official_player->user->year_level ?? 'N/A');
            $table->addCell(2000)->addText($official_player->user->campus->name ?? 'N/A');
            $table->addCell(2000)->addText($official_player->user->sport->name ?? 'N/A');
            $table->addCell(2000)->addText($official_player->coach ?? 'N/A');
            $table->addCell(2000)->addText($official_player->created_at ? $official_player->created_at->format('Y-m-d') : 'N/A');
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'official_players_report_') . '.docx';
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }


    /* Registered Participants Excel */
    protected function generateRegisteredParticipantExcel($results, $startDate, $endDate)
    {
        $query = ActivityRegistration::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with('user')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setTitle('Registered Participants Report');
        $sheet->setCellValue('A1', 'Bataan Peninsula State University')
            ->setCellValue('A2', 'Bind Together')
            ->setCellValue('A3', "Registered Participants Report for $startDate - $endDate");


        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('800000');
        $sheet->getStyle('A3')->getFont()->setSize(12);


        $sheet->setCellValue('A5', 'Name')
            ->setCellValue('B5', 'Year Level')
            ->setCellValue('C5', 'Campus')
            ->setCellValue('D5', 'Email')
            ->setCellValue('E5', 'Height')
            ->setCellValue('F5', 'Weight')
            ->setCellValue('G5', 'Person to Contact')
            ->setCellValue('H5', 'Emergency Contact');


        $sheet->getStyle('A5:H5')->getFont()->setBold(true);


        $row = 6;
        foreach ($results as $registered_participant) {
            $sheet->setCellValue("A$row", $registered_participant->user->firstname . ' ' . $registered_participant->user->lastname);
            $sheet->setCellValue("B$row", $registered_participant->user->year_level);
            $sheet->setCellValue("C$row", $registered_participant->user->campus);
            $sheet->setCellValue("D$row", $registered_participant->user->email);
            $sheet->setCellValue("E$row", $registered_participant->height);
            $sheet->setCellValue("F$row", $registered_participant->weight);
            $sheet->setCellValue("G$row", $registered_participant->emergency_contact);
            $sheet->setCellValue("H$row", $registered_participant->user->contact);
            $row++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        $tempFile = tempnam(sys_get_temp_dir(), 'registered_participants_report_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }


    /* Official Players Excel */
    protected function generateOfficialParticipantExcel($results, $startDate, $endDate)
    {
        $query = ActivityRegistration::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $query->with(['user.sport', 'user.campus'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setTitle('Official Players Report');
        $sheet->setCellValue('A1', 'Bataan Peninsula State University')
            ->setCellValue('A2', 'Bind Together')
            ->setCellValue('A3', "Official Players Report for $startDate - $endDate");


        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('800000');
        $sheet->getStyle('A3')->getFont()->setSize(12);


        $sheet->setCellValue('A5', 'Student Number')
            ->setCellValue('B5', 'Name')
            ->setCellValue('C5', 'Year Level')
            ->setCellValue('D5', 'Campus')
            ->setCellValue('E5', 'Sports Name')
            ->setCellValue('F5', 'Coach')
            ->setCellValue('G5', 'Date Registered');


        $sheet->getStyle('A5:G5')->getFont()->setBold(true);


        $row = 6;
        foreach ($results as $official_player) {
            $sheet->setCellValue("A$row", $official_player->user->student_number ?? 'N/A');
            $sheet->setCellValue("B$row", $official_player->user->firstname . ' ' . $official_player->user->lastname ?? 'N/A');
            $sheet->setCellValue("C$row", $official_player->user->year_level ?? 'N/A');
            $sheet->setCellValue("D$row", $official_player->user->campus->name ?? 'N/A');
            $sheet->setCellValue("E$row", $official_player->user->sport->name ?? 'N/A');
            $sheet->setCellValue("F$row", $official_player->user->coach->name ?? 'N/A');
            $sheet->setCellValue("G$row", $official_player->created_at);

            $row++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        $tempFile = tempnam(sys_get_temp_dir(), 'official_players_report_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    /* Coach List PDF */
    protected function generateCoachesPDF($results, $startDate, $endDate)
    {
        $coaches = User::role(UserTypeEnum::COACH);


        if ($startDate && $endDate) {
            $coaches->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $coaches->get();
        // Load the selected view with the data
        $pdf = Pdf::loadView('admin-sport.reports.coach-list-report', [
            'coaches' => $coaches,
            'results' => $results,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('a4', 'landscape');

        // Stream the PDF file with the current date in the filename
        return $pdf->stream('coaches_report_' . now()->format('Y_m_d') . '.pdf');
    }


    /* Coaches List Docx */
    protected function generateCoachesDocx($results, $startDate, $endDate)
    {

        $coaches = User::role(UserTypeEnum::COACH);


        if ($startDate && $endDate) {
            $coaches->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $coaches->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText("Bataan Peninsula State University", ['bold' => true, 'size' => 16, 'color' => '800000']);
        $section->addText("Bind Together", ['bold' => true, 'size' => 14, 'color' => '800000']);
        $section->addText("Coaches List Report for $startDate - $endDate", ['bold' => true, 'size' => 12]);
        $section->addTextBreak(1);

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText("Name", ['bold' => true]);
        $table->addCell(2000)->addText("Gender", ['bold' => true]);
        $table->addCell(2000)->addText("Email", ['bold' => true]);
        $table->addCell(2000)->addText("Sports", ['bold' => true]);
        $table->addCell(2000)->addText("Date", ['bold' => true]);

        foreach ($results as $coach) {
            $table->addRow();
            $table->addCell(2000)->addText($coach->firstname . ' ' . $coach->lastname ?? 'N/A');
            $table->addCell(2000)->addText($coach->gender ?? 'N/A');
            $table->addCell(2000)->addText($coach->email ?? 'N/A');
            $table->addCell(2000)->addText($coach->sport->name ?? 'N/A');
            $table->addCell(2000)->addText($coach->created_at ?? 'N/A');
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'coaches_list_report') . '.docx';
        $phpWord->save($tempFile, 'Word2007');

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }


    /* Coaches List Excel */
    protected function generateCoachesExcel($results, $startDate, $endDate)
    {
        $coaches = User::role(UserTypeEnum::COACH);


        if ($startDate && $endDate) {
            $coaches->whereBetween('created_at', [$startDate, $endDate]);
        }

        $results = $coaches->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setTitle('Registered Participants Report');
        $sheet->setCellValue('A1', 'Bataan Peninsula State University')
            ->setCellValue('A2', 'Bind Together')
            ->setCellValue('A3', "Registered Participants Report for $startDate - $endDate");


        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('800000');
        $sheet->getStyle('A3')->getFont()->setSize(12);


        $sheet->setCellValue('A5', 'Name')
            ->setCellValue('B5', 'Gender')
            ->setCellValue('C5', 'Email')
            ->setCellValue('D5', 'Sports')
            ->setCellValue('E5', 'Date');


        $sheet->getStyle('A5:H5')->getFont()->setBold(true);


        $row = 6;
        foreach ($results as $coaches) {
            $sheet->setCellValue("A$row", $coaches->firstname . ' ' . $coaches->lastname ?? 'N/A');
            $sheet->setCellValue("B$row", $coaches->gender ?? 'N/A');
            $sheet->setCellValue("C$row", $coaches->email ?? 'N/A');
            $sheet->setCellValue("D$row", $coaches->sport->name ?? 'N/A');
            $sheet->setCellValue("D$row", $coaches->created_at ?? 'N/A');

            $row++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        $tempFile = tempnam(sys_get_temp_dir(), 'coaches_report_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
}
