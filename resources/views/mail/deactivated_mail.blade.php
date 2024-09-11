<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
</head>
<body>
<div class="email-container">
    <p class="email-text">Hello, {{$name}}</p>
    <p>Click the link below to restore your account</p>
    <a href="{{ $restoreLink }}" class="email-link">Reset Password</a>
    <p>Best of wishes, team Toolan.</p>
    <img src="{{ asset('uploads/toolan.png') }}" alt="Logo" class="email-logo">
</div>
</body>
</html>
