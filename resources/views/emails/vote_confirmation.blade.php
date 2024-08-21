<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #4CAF50;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }
        .question {
            font-weight: bold;
            margin-top: 15px;
            font-size: 16px;
            color: #2C3E50;
        }
        .answer {
            font-size: 14px;
            color: #555;
            margin-left: 20px;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Voting!</h1>
        <p>Dear {{ $vote->user->name }},</p>
        <p>Thank you for participating in the election <strong>{{ $vote->election->title }}</strong>.</p>
        <p>Your vote has been successfully recorded.</p>

        <h3>Your Answers:</h3>
        <ul>
            @foreach($vote->answers->groupBy('question_id') as $questionId => $answers)
                <li class="question">{{ $answers->first()->question->question }}:</li>
                <ul>
                    @foreach($answers as $answer)
                        <li class="answer">{{ $answer->answer }}</li>
                    @endforeach
                </ul>
            @endforeach
        </ul>

        <div class="footer">
            <p>Thank you for making your voice heard!</p>
        </div>
    </div>
</body>
</html>
