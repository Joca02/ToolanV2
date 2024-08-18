<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://kit.fontawesome.com/a6397c1be2.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
{{--<script src="js/register.js"></script>--}}
</head>

<body>
<div id="global_login_container">
    <div id="around_login_form">
        <img id="logoImage" src="uploads/toolan.png" alt="">
        <br><br>
        <div id="register_form" class="login_form">
            <div id="login_form_header">
                <span>Create a new account</span>
                <br><br>
            </div>
            <form method="POST" action="{{route('register')}}" id="registrationForm">
                @csrf
                <input type="text" name="name" id="name" placeholder="Enter Your Name" class="login_input" required><br>

                <span id="usernameError" class="text-danger" style="display:none;"></span>
                <input type="text" name="username" id="username" placeholder="New Unique Username" class="login_input" required><br>

                <span id="emailError" class="text-danger" style="display:none;"></span>
                <input type="email" name="email" id="email" placeholder="Email" class="login_input" required><br>

                <input type="password" name="password" id="password" placeholder="New Password" class="login_input" required><br>
                <input type="password" name="password2" id="password2" placeholder="Re-Enter Password" class="login_input" required><br>

                <div class="choose_gender">
                    <label id="chg">Choose gender:</label>
                    <input type="radio" name="gender" value="male" required>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" value="female" required>
                    <label for="female">Female</label><br>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" id="login_submit">Register Now</button>
            </form>
            <br>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let usernameAvailable = true;
        let emailAvailable = true;
        $('#username, #email').on('input', function() {
            let fieldName = $(this).attr('name');
            let fieldValue = $(this).val();
            let inputField = $(this);
            let errorField = $('#' + fieldName + 'Error');

            $.ajax({
                url: '{{ route("checkInputField") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    fieldName: fieldName,
                    fieldValue: fieldValue
                },
                success: function(response) {
                    if (response.available === false) {
                        inputField.addClass('warning');
                        errorField.text(fieldName.charAt(0).toUpperCase() + fieldName.slice(1) + ' is already taken.');
                        errorField.show();

                        if (fieldName === 'username') {
                            usernameAvailable = false;
                        } else if (fieldName === 'email') {
                            emailAvailable = false;
                        }
                    } else {
                        inputField.removeClass('warning');
                        errorField.hide();

                        if (fieldName === 'username') {
                            usernameAvailable = true;
                        } else if (fieldName === 'email') {
                            emailAvailable = true;
                        }
                    }
                }
            });
        });


        $('#registrationForm').on('submit', function(e) {
            let password = $('#password').val();
            let confirmPassword = $('#password2').val();

            if (!usernameAvailable || !emailAvailable) {
                e.preventDefault();
                alert('Please make sure the username and email are available.');
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Please make sure your passwords match.');
            }

            if(password.length<6){
                e.preventDefault();
                alert('Password must have at least 6 letters');
            }
        });
    });
</script>
</body>
</html>
