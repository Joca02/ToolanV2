<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>
<body>
@if(session('success'))
    <div class="alert alert-success text-center" style="margin-bottom: 0">
        {{ session('success') }}
    </div>
@elseif(session('failure'))
    <div class="alert alert-danger text-center" style="margin-bottom: 0">
        {{ session('failure') }}
    </div>
@endif
<div id="global_login_container">

    <div id="around_login_form">
        <img id="logoImage" src="{{asset('/uploads/toolan.png')}}" alt="logo">
        <br><br>
        <div class="login_form">
            <div id="login_form_header">
                <span>Log Into TOOLAN</span>
                <br><br>
            </div>
            <form action="{{route('login')}}" method="post">
                @csrf
                <input type="text" name="username" id="username" placeholder="Username" class="login_input"><br>
                <input type="password" name="password" id="password" placeholder="Password" class="login_input" required><br>
                <button type="submit" class="btn btn-primary" id="login_submit">Log In</button>
            </form>
            <br>
            <a href="{{route('register')}}" id="login_acc_create">Dont have an account? Sign up now!</a>
            <br>
            <a href="#" id="forgot_password_link" data-toggle="modal" data-target="#forgotPasswordModal" style="color:plum;">Forgot Password?</a>
        </div>
    </div>
</div>

<!-- Modal for Password Reset -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="passwordResetForm">
                    @csrf
                    <div class="form-group">
                        <label for="resetEmail">Enter your email address:</label>
                        <input type="email" class="form-control" id="resetEmail" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#passwordResetForm").on('submit', function(e) {
            e.preventDefault();

            var email = $("#resetEmail").val();

            $.ajax({
                url: '/password-reset',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    email: email
                },
                success: function(response) {
                    if(response.status === 'success') {
                        alert('Password reset link has been sent to your email.');
                        $('#forgotPasswordModal').modal('hide');
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('There was an error processing your request. Please try again later.');
                }
            });
        });
    });
</script>
</body>
</html>
