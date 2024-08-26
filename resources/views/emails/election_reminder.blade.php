<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #495057;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #0069d9;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .content h2 {
            font-size: 20px;
            color: #0069d9;
            margin-top: 0;
        }
        .content p {
            margin: 15px 0;
        }
        .content .date {
            font-weight: bold;
            color: #343a40;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .button {
            background-color: #0069d9;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            background-color: #f8f9fa;
            color: #6c757d;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        .footer a {
            color: #0069d9;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="header">
            <h1>Election Reminder</h1>
        </div>

        <!-- Email Content -->
        <div class="content">
            <h2>Don't Forget to Vote!</h2>
            <p>Dear {{ $user->name }},</p>
            <p>This is a friendly reminder that the <strong>{{ $election->title }}</strong> election is currently ongoing.</p>
            <p>Please make sure to cast your vote before the deadline:</p>
            <p class="date">End Date: {{ date('F j, Y, g:i A',strtotime($election->end_date)) }}</p>
            <div class="button-container">
                <a href="{{ $voting_link }}" class="button">Vote Now</a>
            </div>
            <p>Thank you for your participation in making this election a success!</p>
        </div>

        <!-- Email Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Organization. All rights reserved.</p>
            <p>Need help? <a href="mailto:support@example.com">Contact Support</a></p>
        </div>
    </div>
</body>
</html>
