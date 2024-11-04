<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 2px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
            height: auto;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .header .title {
            font-size: 20px;
            font-weight: bold;
            color: #007BFF;
        }

        h2 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 15px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        .note {
            color: #666;
        }

        .note strong {
            font-weight: bold;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="title">Super Admin</div>
        </div>
        <h2>Acknowledgment of Your Recent Feedback</h2>
        <p>Hello {{ $recipientName }},</p>
        <p>We have received your message and carefully reviewed your recent feedback. Thank you for sharing your input with us.</p>
        <p><strong>Our Response:</strong></p>
        <p>{{ $responseMessage }}</p>
        <p>We highly value your contribution and encourage you to continue sharing your thoughts. If you have further questions or ideas, don’t hesitate to reach out to us.</p>
        <p>Best regards,</p>
        <p><strong>{{ $adminName }}</strong><br>
           Super Admin</p>
        <p class="note"><strong>NOTE:</strong> This is a system-generated email. Please do not reply.</p>
    </div>
</body>

</html>
