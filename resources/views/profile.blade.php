
{{--//require_once "classes.php";--}}
{{--//require_once "database.php";--}}
{{--//session_start();--}}
{{--//$userProfile;--}}
{{--//if (!isset($_SESSION['user'])) {--}}
{{--//  header("Location: login.php");--}}
{{--//  exit();--}}
{{--//}--}}
{{--//$currentUser=$_SESSION['user'];--}}
{{--//--}}
{{--//if(isset($_GET['id']))--}}
{{--//{--}}
{{--//  if($_GET['id']==$currentUser->id_user)--}}
{{--//    $userProfile=$currentUser;--}}
{{--//  --}}
{{--//    else--}}
{{--//  {--}}
{{--//    $profile_id=$_GET['id'];--}}
{{--//    $dbc=createConnection();--}}
{{--//--}}
{{--//    $query="SELECT * FROM users WHERE id_user=$profile_id";--}}
{{--//    try{--}}
{{--//      $result=mysqli_query($dbc,$query);--}}
{{--//      if(mysqli_num_rows($result)==1)--}}
{{--//      {--}}
{{--//        $row=mysqli_fetch_assoc($result);--}}
{{--//                  --}}
{{--//        $userProfile=generateUser($row);--}}
{{--//      }--}}
{{--//      else--}}
{{--//        error_log("Several users with same id in profile.php Error");--}}
{{--//    }catch(Exception $e)--}}
{{--//    {--}}
{{--//      error_log("Exception caught in finding user id ".$e);--}}
{{--//    }--}}
{{--//    finally{--}}
{{--//      closeConnection($dbc);--}}
{{--//    }--}}
{{--//    --}}
{{--//  }--}}
{{--// --}}
{{--//}--}}
{{--//--}}
{{--//!---->--}}



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
    <script src="https://kit.fontawesome.com/a6397c1be2.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/post.js') }}"></script>
</head>
<body>


<!--NAVIGATION BAR-->
<div class="container-fluid">
  <div class="row" id="upper-panel">
    <div class="col">
      <a href="home"><img src="/uploads/toolan.png" alt="logo" id="logo"></a>
    </div>
    <div class="col-6" id="search-div">
    <input type="text" class="form-control" id="search" placeholder="Search...">
    </div>
    <div class="usrBar col">
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php //echo "<span class='currUserName'>".$currentUser->name."</span>";//prikaz ulgoovanog usera
          ?>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="profile.php?id={{ Auth::user()->id_user }}">View Profile</a>
            <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
            <a class="dropdown-item" href="{{route('logout')}}">Log Out</a>
        </div>
          <img src="/{{ Auth::user()->profile_picture }}" class='pfpNav' data-userid="{{ Auth::user()->id_user }}">
      </div>
    </div>
  </div>
</div>


<!--PAGE-->
<div class="container-fluid text-center" id="home-container">
    <div class="row">
        <div class="col">
        </div>
        <div class="post col-7">
            <div id="suggestion-box" class="list-group"></div>
            <div class='profile-box'><br>
                <div>
                    <img src="{{ asset($userProfile->profile_picture) }}" class='pfpProfile'>
                </div>

                <p class='profileName'>{{ $userProfile->name }}</p>
                <p class='profileUsername'>{{ '@'.$userProfile->username }}</p>
                <br>
                <button type="button" class="btn btn-primary" id="addORedit_btn"></button>
                <p id='profileDescription'></p>
            </div>
        </div>
        <div class="col">
        </div>
    </div>

    <div>
        <div class="row">
            <div class="col">
            </div>
            <div class="col-6" id="post-container">
            </div>
            <div class="col">
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function() {
        var pageID = '{{ $userProfile->id_user }}';
        let route="/profile/posts";
        console.log(route+" "+pageID)
        loadPosts(route,pageID);

        $(window).scroll(function () {
            if (Math.ceil($(document).height() - $(window).scrollTop()) <= $(window).height() + 50) {
                loadPosts(route,pageID);
            }
        });

        function followButtonTextChange() {
                var btn = $("#addORedit_btn");
                var currentUserID = '{{ $currentUser->id_user }}';
                var profileID = '{{ $userProfile->id_user }}';

                if (profileID == currentUserID) {
                    btn.html("Edit profile");
                } else {
                    $.get("/user/follow", { id: profileID }, function(response) {
                        if (response == "FOLLOWING")
                            btn.html("Unfollow").css("background-color", "grey");
                        else if (response == "FOLLOWING ME")
                            btn.html("Follow Back").css("background-color", "#db4ba6");
                        else
                            btn.html("Follow").css("background-color", "#db4ba6");
                    });
                }
            }

        followButtonTextChange();

        function handleEditOrFollow() {
                var currentUserID = '{{ $currentUser->id_user }}';
                var profileID = '{{ $userProfile->id_user }}';

                if (profileID == currentUserID) {
                  {{--//  window.location.href = "{{ route('editProfile') }}";--}}
                } else {
                    $.post("/user/follow", { id: profileID }, function(response) {
                            followButtonTextChange();
                    });
                }
            }

        $("#addORedit_btn").click(handleEditOrFollow);

        var decodedDescription = decodeURIComponent("{{ $userProfile->prof_description }}");
        if (decodedDescription.length > 0)
                $("#profileDescription").text(decodedDescription);
        else
                $("#profileDescription").text("No profile description");

    });
</script>

</body>
</html>
