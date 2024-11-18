<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Participants List Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            height: auto;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        h3 {
            font-size: 16px;
            font-weight: normal;
            /*    margin: 5px 0; */
        }

        /*  .contact-info {
            margin-top: 5px;
            font-size: 12px;
            text-align: center;
            color: #800000;
        }
 */
        .report-info {
            margin-top: 5px;
            /*   margin-bottom: 20px; */
            text-align: center;
        }

        .report-info p {
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature .signature-line {
            margin-top: 50px;
            border-top: 1px solid black;
            width: 200px;
            text-align: center;
        }

        .footer {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
        <!-- Logo -->
        <img src="{{ public_path('images/bindtogether-logo.png') }}" alt="University Logo" style="width: 100px; margin-left: 100px;">

        <!-- Header Content -->
        <div class="header" style="text-align: center;">
            <h1 style="color: #800000; margin: -70px;">Bataan Peninsula State University</h1>
            <h3 style="color: #800000; margin:  70px">Bind Together</h3>
            <div class="contact-info" style="color: #800000;">
                <p style="margin: -65px">City of Balanga, 2100 Bataan</p>
                <p style="margin-top: 65px;">Tel: (047) 237-3309 | www.bpsu.edu.ph | Email: bpsu.bindtogether@gmail.com</p>
                <h3>Activities Report for {{ $startDate }} - {{ $endDate }}</h3>
                <p style="font-weight: bold;">
                    Type of Report <span style="font-weight: normal;">(Registered Participants Report)</span>
                </p>
            </div>
        </div>

    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Year Level</th>
                <th>Campus</th>
                <th>Email</th>
                <th>Height</th>
                <th>Weight</th>
                <th>Person to Contact</th>
                <th>Emergency Contact Number</th>
                <th>Date Registered</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registered_participants as $participants)
            <tr>
                <td>{{ $participants->user->firstname . ' ' . $participants->user->lastname }}</td>
                <td>{{ $participants->user->year_level }}</td>
                <td>{{ $participants->user->campus->name }}</td>
                <td>{{ $participants->user->email }}</td>
                <td>{{ $participants->height }}</td>
                <td>{{ $participants->weight }}</td>
                <td>{{ $participants->emergency_contact }}</td>
                <td>{{ $participants->user->contact}}</td>
                <td>{{ (new DateTime($participants->user->created_at ))->format('Y-m-d');}}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

    <div class="signature" style="float: right">
        {{-- <p>Reported by:</p> --}}
        <div class="signature-line" style="font-size: 12px">
            (Space for Signature)
        </div>
        {{-- <p>(name of the report generator)</p> --}}
    </div>

    {{-- <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d') }}</p>
    </div> --}}
</body>

</html>