<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
</head>
<body>
<div class="email-container">
    <p class="email-text">Hello, {{$name}}</p>
    <p class="email-text">Please click the link below to verify your email address:</p>
    <a href="{{ $verificationUrl }}" class="email-link">Verify Email</a>
    <p class="email-text">Thank you for joining us!</p>
    <img src="{{ asset('uploads/toolan.png') }}" alt="Logo" class="email-logo">
</div>
</body>
</html>
