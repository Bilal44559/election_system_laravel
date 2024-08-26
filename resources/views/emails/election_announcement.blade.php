<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Election Announcement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333333;
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
            background-color: #004aad;
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
            color: #004aad;
            margin-top: 0;
        }
        .content p {
            margin: 15px 0;
        }
        .content .date {
            font-weight: bold;
            color: #444444;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777777;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Email Header -->
        <div class="header">
            <h1>Election Announcement</h1>
        </div>

        <!-- Email Content -->
        <div class="content">
            <h2>{{ $election->title }}</h2>
            <p>{{ $election->description }}</p>
            <p class="date">Start Date: {{ date('F j, Y',strtotime($election->start_date)) }}</p>
            <p class="date">End Date: {{ date('F j, Y',strtotime($election->end_date)) }}</p>
            <p>Thank you for participating in the election process!</p>
        </div>

        <!-- Email Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Organization. All rights reserved.</p>
            <p>If you have any questions, please <a href="mailto:support@example.com">contact us</a>.</p>
        </div>
    </div>
</body>
</html>
