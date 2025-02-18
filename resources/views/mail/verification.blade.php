<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #0056b3;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }

        .button {
            display: inline-block;
            background-color: #0056b3;
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 20px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>

        <div class="content">
            <p>Thank you for registering with us! To complete your registration, please verify your email address by entering the code below:</p>
            <h2 style="font-size: 30px; color: #333;">{{$code}}</h2>
            <p>If you did not request this verification, please ignore this email or contact our support.</p>
        </div>

        <div class="footer">
            <p>If you have any questions, feel free to <a href="mailto:support@example.com">contact us</a>.</p>
            <p>&copy; 2025 Your Company. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
