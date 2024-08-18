
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
    <div class="alert alert-success" style="margin-bottom: 0">
        {{ session('success') }}
    </div>
@elseif(session('failure'))
    <div class="alert alert-danger" style="margin-bottom: 0">
        {{ session('failure') }}
    </div>
@endif
<div id="global_login_container">

    <div id="around_login_form">
        <img id="logoImage" src="{{asset('uploads/toolan.png')}}" alt="logo">
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
        </div>
    </div>
</div>


<script>
    // $(function(){
    //     var btn=$("#login_submit");
    //     var form = $("form");
    //     btn.click(function(){
    //         var usr=$("#username").val();
    //         var psw=$("#password").val();
    //         if(usr.length<2)
    //             alert("Username must hold at least 2 characters");
    //         else if (psw.length<2)
    //             alert("Password must hold at least 2 characters");
    //         else
    //         {
    //             $.post("check_login.php",{username:usr,password:psw},
    //                 function(response)
    //                 {
    //                     if(response=="admin success")
    //                     {
    //                         window.location.href="admin_home.php";
    //                     }
    //                     else if(response=="success")
    //                     {
    //                         window.location.href ="home.php";
    //                     }
    //                     else if(response.status=="banned")
    //                     {
    //                         alert("You have been banned until:   "+response.date_end+"\nFor reason:\n"+response.banReason);
    //                     }
    //                     else
    //                     {
    //                         alert("Username and password don't match");
    //                     }
    //                 })
    //         }
    //
    //     })
    //
    //     form.submit(function(event) {
    //         event.preventDefault();
    //     });
    // })
</script>
</body>
</html>
