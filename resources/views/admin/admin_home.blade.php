<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
                    <a class="dropdown-item" href="{{route('logout')}}">Log Out</a>
                </div>

                <img src="/{{ Auth::user()->profile_picture }}" class='pfpNav' data-userid="{{ Auth::user()->id_user }}">
            </div>
        </div>
    </div>
</div>



<!--HOME PAGE-->
<div class="container-fluid text-center" id="home-container">

    <div>
        <div class="row">
            <div class="col">
            </div>
            <div class="col-7" id="post-container">
                <div id="suggestion-box" class="list-group"></div>
                <br><br>
                <button type="button" class="btn btn-primary btn-lg btn-block" id="bannedUsersBtn">View All Banned Users</button>
                <!--USER BANS MODAL-->
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
                                <!-- Content -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END-->
            </div>
            <div class="col">
            </div>
        </div>
    </div>
</div>



</body>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function(){

        var pageID=0;
        var route='/admin/posts'
        var isAdminView=true;

        loadPosts(route,pageID,isAdminView);

        $(window).scroll(function() {

            if (Math.ceil($(document).height() - $(window).scrollTop()) <= $(window).height()+50) {
                loadPosts(route,pageID,isAdminView);
            }
        });


        $("#bannedUsersBtn").click(function(){
            $.get("/admin/banned-users",function(response)
            {
                var modalBody = $('#windowModalBody');
                modalBody.empty();
                $("#windowModalLabel").text("Banned users")
                if(response.length > 0) {
                    for(var i = 0; i < response.length; i++) {
                        var user = response[i];

                        var imgSrc = user.profile_picture;
                        var dataAttr = user.profile_picture === 'uploads/profile_pictures/default.png'
                            ? `data-userid="${user.id_user}"`
                            : '';

                        modalBody.append("<div class='d-flex align-items-center justify-content-between mb-2'>" +
                            "<div class='d-flex align-items-center'>" +
                            "<img src='/" + imgSrc + "' class='pfpNav' " + dataAttr + ">" +
                            "<span class='ml-2'>" + user.name + "</span>" +
                            "</div>" +
                            "</div>");

                    }
                }
                else modalBody.append('<p>0 users are banned currently.</p>');


                $('#windowModal').modal('show');
            });
        })

        $.post("update_bans.php");  //ako je prosao ban korisniku, da mi se ne prikazuje u listi korisnika koji su jos pod banom

    });

</script>

</html>
