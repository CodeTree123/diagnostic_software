<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailData['subject'] }}</title>
</head>

<body>
    <div class="email-container">
        <h2>{{ $emailData['subject'] }}</h2>
        <p>From: {{ $emailData['sender'] }}</p>
        <div class="content">
            <p>{{ $emailData['body'] }}</p>
        </div>
        <div class="footer">
            <img src="{{asset('newAssets/images/codetreelogo.png')}}" alt="Company Logo">
            <p>Email: codetreebd@gmail.com</p>
        </div>
    </div>
</body>

</html>