<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Email</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Nunito Sans', sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 0 auto;
            max-width: 1000px;
            text-align: left;
        }
        h5 {
            color: #88B04B;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 16px;
        }
        p {
            color: #333;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }
        .highlight {
            color: #2a2a2a;
            font-weight: 700;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="card">
    <p>Dear <span class="highlight">{{$name}}</span>,</p>
    <p>For this event is in very critical  <b>{{ $complianceEvent->name }}</b> in <b>{{ $complianceEvent->country->name }}</b>.</p>

{{--    <div class="footer">--}}
{{--        Regards,<br>--}}
{{--        Compliance--}}
{{--    </div>--}}
</div>
</body>
</html>
