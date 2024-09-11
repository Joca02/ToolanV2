<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/mail.css') }}">
</head>
<body>
<div class="email-container">
    <p class="email-text">Hello, {{$name}}</p>
    <p class="email-text">Click the link below to reset your password:</p>
    <a href="{{ $resetLink }}" class="email-link">Reset Password</a>
    <p class="email-text">If you did not request a password reset, no action is required.</p>
    <img src="{{ asset('uploads/toolan.png') }}" alt="Logo" class="email-logo">
</div>
</body>
</html>
