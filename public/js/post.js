var postsLimit=3;
var offset = 0;
var isLoading = false;
var postElement = null;
var likeCounts={};
function loadPosts(route, pageID) {
    if (isLoading) return;  // Prevent further requests while loading

    isLoading = true;  // Set isLoading to true to block other requests

    $.get("/post.html")
        .done(function(data) {
            var tempContainer = $('<div>');
            tempContainer.html(data);

            postElement = tempContainer.find('.the-post');

            // Fetch posts after loading the template
            $.get(route, { userId: pageID, limit: postsLimit, offset: offset })
                .done(function(response) {
                    for (var i = 0; i < response.length; i++) {
                        var post = response[i];

                        likeCounts[post.id_post] = post.likesCount;
                        if ($('#' + post.id_post).length === 0) {
                            var newPost = postElement.clone(); // Clone the template

                            newPost.attr('id', post.id_post);
                            newPost.find('.pfpNav').attr('src', '/' + post.profile_picture);
                            newPost.find('.pfpNav').data('userid', post.id_user);
                            newPost.find('.username').html("<strong>@" + post.username + "</strong>");
                            newPost.find('.timestamp').text(post.date);
                            newPost.find('.likesCount').html(post.likesCount + " Likes");
                            newPost.find('.commentsCount').html(post.commentsCount + " Comments");

                            var likeButton = newPost.find('.like');
                            if (post.isPostLiked) {
                                likeButton.removeClass('fa-heart-o').addClass('fa-heart');
                            } else {
                                likeButton.removeClass('fa-heart').addClass('fa-heart-o');
                            }

                            if (post.picture === null) {
                                newPost.find('.post-content p').text(post.post_description);
                                newPost.find('.post-footer p').remove();
                            } else {
                                newPost.find('.post-content p').remove();
                                newPost.find('.post-content').append('<img src="/' + post.picture + '" alt="Post Image" class="post-picture">');
                                newPost.find('.post-footer p').html('<u>Description:</u> ' + post.post_description);
                            }

                            if (post.isUserOwner) {
                                newPost.find('.deletePost').append("<button type='button' class='delBtn btn btn-outline-danger'>X</button>");
                            }

                            $("#post-container").append(newPost); // Add the new post to the container
                        }
                    }
                    offset += postsLimit;
                    isLoading = false;  // Allow further requests
                });
        });
}


    $(document).on('click', '.pfpNav', function() {
        //nalazim userID trimovanjem sourca slike, jer je svaka slika u formatu userID.png/jpg/jpeg..
        var srcValue = $(this).attr('src');
        var idDotPng=srcValue.replace('/uploads/profile_pictures/', '');
        var arr=idDotPng.split('.');
        var userID=arr[0];
        console.log(userID)
        if(userID!="default")
          window.location.href = "/user/profile?id="+userID;
        else
          {
              var alternativeUserID = $(this).data('userid');
                console.log("ALT "+alternativeUserID)
              if (!isNaN(alternativeUserID) && alternativeUserID !== '') {
                  window.location.href = "/user/profile?id=" + alternativeUserID;
              } else {
                  console.log("Unable to find user ID using alternative method");
              }
          }

    });

   //lajk event
$(document).on('click', '.like', function(){
    var postID = $(this).closest('.the-post').prop('id');
    var likeButton = $(this); // reference to the clicked like icon

    $.post(
        "/user/like",
        {
            postId: postID,
        },
        function(response) {
            console.log(response);
            if (response == "liked") {
                likeButton.removeClass('fa-heart-o').addClass('fa-heart');
                likeCounts[postID]++;
            } else if (response == "notLiked") {
                likeButton.removeClass('fa-heart').addClass('fa-heart-o');
                likeCounts[postID]--;
            }
            likeButton.closest('.the-post').find('.likesCount').html(likeCounts[postID]+" Likes");
        }
    );
})

  //comment event
  $(document).on('click', '.comment', function(){
    var postID = $(this).closest('.the-post').prop('id');
    var commentButton=$(this);
    var txtArea=document.getElementById("commentText");
    txtArea.value="";
    var subm=document.getElementById("submitComment");
      buttonEnabled(txtArea,subm);
    $("#commentText").on('input',function(){
      buttonEnabled(txtArea,subm);
    })

    $('#commentModal').modal('show');
    var btn=$("#submitComment");
    btn.unbind("click");  //svaki put kada se klikne comment ico novi event handler se dodaje dugmetu koje submituje com pa moram da ga unbindujem
    btn.click(function(){
      var comment=txtArea.value;
      console.log(comment.length);
      if (comment.length<30)
      {
          $.post(
          "/user/comment",{postId:postID,comment:comment},
          function(response){
            if(response=="success")
            {
              alert("You have successfully posted your comment.");
              $('#commentModal').modal('hide');
              var postElement = $('#' + postID);
              var commentsCountContainer = postElement.find('.commentsCount');
              var currentCommentCount = parseInt(commentsCountContainer.text().split(' ')[0]);
              commentsCountContainer.text((currentCommentCount + 1) + " Comments");
            }
          }
        );
      }
      else alert("Comment can have a maximum of 30 characters!");

    });
  });


  //ko je lajkovao
  $(document).on('click', '.likesCount', function(){
    var postID = $(this).closest('.the-post').prop('id');
    $.get("/user/like",{postId:postID},
    function(response)
    {
      var modalBody = $('#windowModalBody');
      modalBody.empty();
      $("#windowModalLabel").text("Users who liked this photo")
      if(response.length>0)
      {
        for(var i=0;i<response.length;i++)
        {
          modalBody.append("<div class='d-flex align-items-center justify-content-between mb-2'>" +
    "<div class='d-flex align-items-center'>" +

    "<img src='/" + response[i].profile_picture + "' class='pfpNav' data-userid='"+response[i].id_user+"'></a>" +
    "<span class='ml-2'>" + response[i].name + "</span>" +
    "</div>" +
    "<i class='like fa fa-heart fa-2x'></i>" +
    "</div>");
        }
      }
      else modalBody.append('<p>This post has 0 likes.</p>');


      $('#windowModal').modal('show');
    })
  });


  //komentari prikaz
  $(document).on('click', '.commentsCount', function(){
    var postID = $(this).closest('.the-post').prop('id');
    $.get("/user/comment",{postId:postID},
    function(response)
    {
      var users=response.users;
      var comments=response.comments;
      var modalBody = $('#windowModalBody');
      modalBody.empty();
      $("#windowModalLabel").text("Comments")

      if(users.length>0)
      {
        for (let i = 0; i < users.length; i++) {
          modalBody.append("<div class='d-flex align-items-center justify-content-start mb-2'>" +
          "<div class='d-flex align-items-center'>" +
          "<img src='/" + users[i].profile_picture + "' class='pfpNav' data-userid='"+users[i].id_user+"'></a>" +
          "<u><span class='ml-2'>" + users[i].name + ":</span></u>" +
          "</div>" +
          "<span class='ml-2'>" + comments[i] + "</span>" +
          "</div>");
        }
      }
      else modalBody.append('<p>This post has 0 comments.</p>');

      $('#windowModal').modal('show');
    })
  })

  //brisanje posta
  $(document).on('click','.delBtn',function()
  {
    if(confirm("Are you sure you want to delete this post? Once deleted, action cannot be undone."))
    {
        var postID = $(this).closest('.the-post').prop('id');
        $.post("delete_post.php",{postID:postID},function(response){
            if(response=="success")
            {
              alert("You have successfully deleted your post.")
              window.location.reload();
            }
            else{
              alert("There was an error during your request. Please try again later.")
            }
        });
      }
  });






  //pretraga korisnika
$(function(){
    const suggestionBox = $("#suggestion-box");

    // Function to fetch and display suggestions
    function fetchSuggestions(characters) {
        suggestionBox.empty(); // Clear previous results

        if(characters.length > 0) {
            $.get("/filter-users?name=" + characters, function(response) {
                if (response.length > 0) {
                    for (var i = 0; i < response.length; i++) {
                        const suggestionItem = $("<a href='profile?id="+response[i].id_user+"' class='list-group-item list-group-item-action list-group-item-light'><img src='/" + response[i].profile_picture + "' class='profile-picture-search'> " + response[i].name + "</a>");
                        suggestionBox.append(suggestionItem);
                    }
                    suggestionBox.show(); // Show the suggestion box
                } else {
                    suggestionBox.hide(); // Hide if no results
                }
            });
        } else {
            suggestionBox.hide(); // Hide the box when input is empty
        }
    }

    // Handle input event (typing in the search bar)
    $("#search").on("input", function(){
        var characters = $(this).val();
        fetchSuggestions(characters);
    });

    // Handle click event (clicking on the search bar)
    $("#search").on("click", function(){
        var characters = $(this).val(); // Get the current value in the input field
        if (characters.length > 0) {
            fetchSuggestions(characters); // Fetch and display suggestions if characters are present
        }
    });

    // Optionally, hide suggestion box when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest("#search-div").length) {
            suggestionBox.hide();
        }
    });
});

