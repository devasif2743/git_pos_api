<!DOCTYPE html>
<html>
<head>
    <title>Meeting Scheduled</title>
</head>
<body>
    <p>Hello,</p>
    <p>Your meeting has been scheduled with the following details:</p>
    <p><strong>Date:</strong> {{ $details['date'] }}</p>
    <p><strong>Time:</strong> {{ $details['time'] }}</p>
    <p><strong>Google Meet Link:</strong> <a href="{{ $details['link'] }}">{{ $details['link'] }}</a></p>
    <p>Thank you,<br>Team Ozrit</p>
</body>
</html>
