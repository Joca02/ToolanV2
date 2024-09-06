<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/reset_password.css') }}">
</head>
<body>
<div id="resetPasswordContainer">
    <img id="logoImage" src="{{asset('/uploads/toolan.png')}}" alt="logo">
    <div class="resetPasswordForm">
        <h2>Reset Password</h2>
        <form action="/reset-password" method="POST" id="resetPasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ request('token') }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            <label for="password">New Password</label>
            <input type="password" name="password" id="password" required>

            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
        var password = document.getElementById('password').value;
        var passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            event.preventDefault();
            return;
        }

        if (password !== passwordConfirmation) {
            alert('Passwords do not match.');
            event.preventDefault();
        }
    });
</script>
</body>
</html>
