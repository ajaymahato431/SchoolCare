<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SchoolCare User Registraton</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: skyblue;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .content p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            margin: 0 0 15px;
        }

        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="header">
            <h1>SchoolCare User Registration Update</h1>
        </div>

        <!-- Email Content -->
        <div class="content">
            <p>Dear {{ $data['name'] }},</p>
            <p>{{ $data['message1'] }}</p>
            <p>{{ $data['message2'] }}</p>
            <p>{{ $data['message3'] }}</p>
            <p>Thank you and regards,<br>SchoolCare Team</p>
        </div>

        <!-- Email Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} SchoolCare. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
