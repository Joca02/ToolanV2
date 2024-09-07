<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Edit profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
</head>
<body>
<div class="container">
    <br>
    <h2>Edit Profile</h2>
    <form id="editProfileForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="editName">Edit Name:</label>
            <input type="text" class="change form-control" id="editName" name="editName" placeholder="Enter your new name">
        </div>
        <div class="form-group">
            <label for="editDescription">Add/Change Profile Description:</label>
            <textarea class="change form-control" id="editDescription" name="editDescription" rows="3" placeholder="Enter your new profile description" style="resize: none;"></textarea>
        </div>
        <div class="form-group">
            <label for="editProfilePicture">Add New Profile Picture:</label>
            <input type="file" class="form-control-file" id="editProfilePicture" name="editProfilePicture">
        </div>
        <br>
        <button type="button" class="btn btn-primary" id="submit">Submit changes</button>
        <button type="button" class="btn btn-secondary" id="cancel">Cancel</button>
        <button type="button" class="btn btn-danger" id="delete">Deactivate Profile</button>
    </form>
    <br>
    <button type="button" class="btn btn-warning" id="resetPasswordBtn">Change Password</button>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function(){
            var btnResetPassword = $("#resetPasswordBtn");
            btnResetPassword.click(function(){
                $.ajax({
                    url: "/password-reset",  // Update to the actual route handling password reset requests
                    type: "POST",
                    data: {
                        email: "{{ Auth::user()->email }}"  // Send the authenticated user's email
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert("Password reset link has been sent to your email.");
                        } else {
                            alert("Error sending reset link: " + response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while sending the password reset link.");
                    }
                });
            });

            // Your existing form handlers remain unchanged
            var name = $("#editName");
            var description = $("#editDescription");
            var profile_picture = $("#editProfilePicture");
            name.val("{{ Auth::user()->name }}");

            var decodedDescription = decodeURIComponent("{{ isset(Auth::user()->prof_description) ? Auth::user()->prof_description : '' }}");
            description.val(decodedDescription);

            var btnSubmit = $("#submit");
            btnSubmit.click(function(){
                var condition = true;
                if(name.val().length < 2) {
                    condition = false;
                    alert("Name must have at least 2 letters!");
                } else if(name.val().length > 15) {
                    condition = false;
                    alert("Name cannot have more than 15 letters!");
                }
                if(description.val().length > 255) {
                    condition = false;
                    alert("Description can have a maximum of 255 characters!");
                }

                if(condition) {
                    var encodedDescription = encodeURIComponent(description.val());
                    var formData = new FormData($("#editProfileForm")[0]);

                    formData.append('editDescription', encodedDescription);
                    $.ajax({
                        url: "/user/edit_profile",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(response) {
                            if (response == "success") {
                                alert("Changes have been saved successfully!");
                                window.location.href = "/user/profile?id={{ Auth::user()->id_user }}";
                            }
                            else if(response == "file_failure") {
                                alert("Please use a different picture format.");
                            }
                            else {
                                alert("No changes were saved.");
                                window.location.href = "/user/profile?id={{ Auth::user()->id_user }}";
                            }
                        }
                    });
                }
            });

            var btnCancel = $("#cancel");
            btnCancel.click(function(){
                alert("No changes were saved.");
                window.location.href = "/user/profile?id={{ Auth::user()->id_user }}";
            });

            var btnDelete = $("#delete");
            btnDelete.click(function(){
                if(confirm("Are you sure you want to deactivate profile?")) {
                    var idUser = {{ Auth::user()->id_user }};

                    $.post(
                        "/user/deactivate-account", {id: idUser}, function(response) {
                            if(response == "success") {
                                alert("Profile has been successfully deactivated. You will be redirected to Log In page.");
                                window.location.href = "/login";
                            } else {
                                alert("There was an error trying to deactivate your profile, please try again later.");
                            }
                        }
                    );
                }
            });

        });
    </script>
</div>
</body>
</html>
