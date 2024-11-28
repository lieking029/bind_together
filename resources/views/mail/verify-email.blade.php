{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
</head>

<body>
    <h1>Verify Your Email Address</h1>
    <p>Hello {{ $name }},</p>
    <p>Please click the button below to verify your email address:</p>
    <a href="{{ $verificationUrl }}"
        style="padding: 10px 15px; background-color: #3498db; color: white; text-decoration: none;">
        Verify Email Address
    </a>
    <p>If you did not create an account, no further action is required.</p>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
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
            <div class="title">Bind Together</div>
        </div>
        <h2>Welcome to Bind Together</h2>
        <p>Dear {{ $user->firstname }} {{ $user->lastname }},</p>
        <p>We are pleased to inform you that your account has been successfully created. Please verify your email
            by clicking the link below:</p>
        <ul>
            <li><strong>Role:</strong> {{ $role }}</li>
            <li><strong>Email:</strong>{{ $user->email }}</li>
            {{-- <li><strong>Verification Code:</strong> ' . $key . '</li> --}}
        </ul>
        <p>Please keep this information confidential and do not share it with others.</p>
        <p>To verify your account, please visit the following link:
            <a href="{{ $verificationUrl }}">
                Verify Email Address
            </a>
        </p>
        <p class="note"><strong>NOTE:</strong> This is a system-generated email. Please do not reply.</p>
    </div>
</body>

</html>