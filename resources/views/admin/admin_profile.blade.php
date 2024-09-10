
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">

    <script src="https://kit.fontawesome.com/a6397c1be2.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
{{--    <script src="js/home.js"></script>--}}
{{--    <script src="js/admin.js"></script>--}}
    <script src="{{ asset('js/home.js') }}"></script>
    <script src="{{ asset('js/post.js') }}"></script>
</head>
<body>


<!--NAVIGATION BAR-->
<div class="container-fluid">
    <div class="row" id="upper-panel">
        <div class="col">
            {{--            <img src="/uploads/toolan.png" alt="logo" id="logo">--}}
            <img src="/uploads/admin.png" alt="logoadmin" id="adminLogo">
        </div>
        <div class="col-6" id="search-div">

            <input type="text" class="form-control" id="search" placeholder="Search...">
            {{--            <img src="/uploads/admin.png" alt="logoadmin" id="adminLogo">--}}

        </div>
        <div class="usrBar col">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class='currUserName'>{{ Auth::user()->name }}</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="profile?id={{ Auth::user()->id_user }}">View Profile</a>
                    <a class="dropdown-item" href="/admin/statistics-page">Statistics</a>
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
            <div class='profile-box'><br><div>
                    <img src="{{ asset($userProfile->profile_picture) }}" class='pfpProfile'>
                </div>
                <!-- BAN MODAL -->
                <div id="ban-modal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title">Ban User</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                            </div>
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="ban-expiration-date">Ban Expiration Date:</label>
                                    <input type="date" id="ban-expiration-date" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="ban-reason">Ban Reason:</label>
                                    <textarea id="ban-reason" class="form-control" rows="4" placeholder="Enter reason for the ban"></textarea>
                                </div>
                            </div>
                            <!-- Modal Footer -->
                            <div class="ban-footer modal-footer">
                                <button id="submit-ban" class="btn subm-ban-btn ">Submit Ban</button>
                                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--USER LIKES MODAL-->
                <div class="modal fade" id="windowModal" tabindex="-1" role="dialog" aria-labelledby="likesModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="windowModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="windowModalBody">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Followers Modal -->
                <div class="modal fade" id="followersModal" tabindex="-1" role="dialog" aria-labelledby="followersModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="followersModalLabel">Followers</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="followersModalBody">
                                <!-- Follower list will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Following Modal -->
                <div class="modal fade" id="followingModal" tabindex="-1" role="dialog" aria-labelledby="followingModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="followingModalLabel">Following</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="followingModalBody">
                                <!-- Following list will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>



                <!--END-->
                <p class='profileName'>{{ $userProfile->name }}</p>
                <p class='profileUsername'>{{ '@'.$userProfile->username }}</p>
                <br>
                <button type="button" class="btn btn-primary" id="ban_btn"></button>
                <div id="profile-stats" class="d-flex justify-content-center">
                    <div class="stat" id="followers-count-container">
                        <span id="followers-count">0</span><br>
                        <span>Followers</span>
                    </div>
                    <div class="stat" id="following-count-container">
                        <span id="following-count">0</span><br>
                        <span>Following</span>
                    </div>
                    <div class="stat">
                        <span id="post-count">0</span><br>
                        <span>Posts</span>
                    </div>
                </div>
                <p id='profileDescription'></p>
            </div></div>

        <div class="col">
        </div>
    </div>

    <div >
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
        $(function(){
            var pageID = '{{ $userProfile->id_user }}';
            var route = "/profile/posts";
            var isAdminView=true;

            function updateProfileStats() {
                $.get("/profile/stats", { id: pageID }, function(response) {
                    $("#followers-count").text(response.followersCount);
                    $("#following-count").text(response.followingCount);
                    $("#post-count").text(response.postCount);
                });
            }

            updateProfileStats();
            loadPosts(route, pageID,isAdminView);

            $(window).scroll(function() {

                if (Math.ceil($(document).height() - $(window).scrollTop()) <= $(window).height()+50) {
                    loadPosts(route, pageID,isAdminView);                }
            });


            //postavljanje boje i teksta dugmeta za ban
            function changeBanButton()
            {
                var admin={{$admin->id_user}};
                if(pageID!=admin)
                {
                    $.get("/admin/user-ban-status",{userId:pageID},function(response){
                        if(response=="banned")
                        {
                            $("#ban_btn").css("background-color", "rgb(76, 201, 93)");
                            $("#ban_btn").text("Unban user");
                        }
                        else
                        {
                            $("#ban_btn").css("background-color", "rgb(196, 68, 68)");
                            $("#ban_btn").text("Ban User");
                        }
                    });
                }
                else
                {
                    $("#ban_btn").css("width", "400px");
                    $("#ban_btn").text("No action can be performed on admin profile");
                }
            }

            changeBanButton();

            //banovanje i unbanovanje korisnika
            $(document).ready(function () {
                var submit = $("#submit-ban");
                submit.click(function () {
                    var banDate = $("#ban-expiration-date").val();
                    var selectedDate = new Date(banDate);
                    var todaysDate = new Date();
                    var txt = $("#ban-reason").val();
                    console.log(txt.length);

                    if (selectedDate > todaysDate) {
                        if (txt.length > 4) {
                            console.log(banDate);
                            $.post("/admin/ban-user", { banDate: banDate, userId: pageID, banReason: txt },
                                function (response) {
                                    if (response == "success") {
                                        alert("Ban has been successfully set until " + banDate);
                                        $("#ban-modal").modal("hide");
                                        changeBanButton();
                                    } else {
                                        alert("There was an error setting the ban. Please try again later.")
                                    }
                                });
                        } else {
                            alert("You must enter a ban reason with a minimum of 5 characters.");
                        }
                    } else {
                        alert("Ban expiration date must be set in the future");
                    }
                });


                $("#ban_btn").click(function () {
                    var admin = {{$admin->id_user}};
                    if (pageID != admin) {
                        $.get("/admin/user-ban-status", { userId: pageID }, function (response) {
                            if (response == "banned") {
                                if (confirm("Are you sure you want to Unban this user?")) {
                                    $.post("/admin/unban-user", { userId: pageID },
                                        function (response) {
                                            if (response == "success") {
                                                alert("User has been successfully unbanned.");
                                                changeBanButton();
                                            } else {
                                                alert("There was a problem during your request. Please try again later.");
                                            }
                                        });
                                }
                            } else {
                                $("#ban-modal").modal("show");
                            }
                        });
                    }
                });
            });


            //profile description
            var decodedDescription = decodeURIComponent("<?php echo $userProfile->prof_description?>")
            if(decodedDescription.length>0)
                $("#profileDescription").text(decodedDescription);
            else $("#profileDescription").text("No profile description");

            // Fetch and display followers
            $("#followers-count-container").click(function() {
                $.get("/followers-info", { id: pageID }, function(response) {
                    var followersList = '';

                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(function(user) {
                            let profilePicture = '/'+user.profile_picture;

                            followersList += `<div class="follower-item">
                    <img src="${profilePicture}" class="pfpNav" data-userid="${user.id_user}">
                    <span>${user.name}</span>
                </div>`;
                        });
                    } else {
                        followersList = "<p>This user has 0 followers.</p>";
                    }

                    $("#followersModalBody").html(followersList);
                    $("#followersModal").modal('show');
                });
            });

// Fetch and display following
            $("#following-count-container").click(function() {
                $.get("/following-info", { id: pageID }, function(response) {
                    var followingList = '';

                    if (Array.isArray(response) && response.length > 0) {
                        response.forEach(function(user) {
                            let profilePicture = '/'+user.profile_picture;

                            followingList += `<div class="following-item">
                    <img src="${profilePicture}" class="pfpNav" data-userid="${user.id_user}">
                    <span>${user.name}</span>
                </div>`;
                        });
                    } else {
                        followingList = "<p>This user doesn't follow anyone.</p>";
                    }

                    $("#followingModalBody").html(followingList);
                    $("#followingModal").modal('show');
                });
            });


        });

    </script>


</body>
</html>
