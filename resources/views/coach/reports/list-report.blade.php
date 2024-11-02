<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        h1,
        h3 {
            color: #800000;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        h3 {
            font-size: 16px;
            font-weight: normal;
            margin: 5px 0;
        }

        .contact-info {
            margin-top: 5px;
            font-size: 12px;
            text-align: center;
            color: red;
        }

        .report-info {
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 30px;
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
    <div class="header">
        <img src="{{ public_path('images/bindtogether-logo.png') }}" alt="Bind Together Logo">
        <div>
            <h1>Bataan Peninsula State University</h1>
            <h3>Bind Together</h3>
            <div class="contact-info">
                <p>City of Balanga, 2100 Bataan</p>
                <p>Tel: (047) 237-3309 | www.bpsu.edu.ph | Email: bpsu.bindtogether@gmail.com</p>
            </div>
        </div>
    </div>

    <div class="report-info">
        <h3>List of Coaches Report for {{ $startDate }} - {{ $endDate }}</h3>
        <p><strong>Type of Report:</strong> (List of Coaches)</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Sports</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->title }}</td>
                <td>{{ $registration->gender }}</td>
                <td>{{ $registration->email }}</td>
                <td>{{ $registration->sports }}</td>
                <td>{{ Carbon::parse($registration->date)->format('F j, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-line">(Space for Signature)</div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d') }}</p>
    </div>
</body>

</html>