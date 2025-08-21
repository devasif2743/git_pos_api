<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
        <p>Hello {{ $data['user_data']->name }},</p>
        <p>Your OTP {{ $data['otp'] }},</p>
        <p>Expired Time {{ $data['exp'] }},</p>
       
        <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html> -->


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
