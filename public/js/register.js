
// function validateForm() {
//     var password1 = document.getElementsByName('password')[0].value;
//     var password2 = document.getElementsByName('password2')[0].value;
//
//     if (password1 !== password2) {
//         alert('Passwords do not match!');
//         return false; //ne prolazi
//     }
//     var username=document.getElementsByName('username')[0].value;
//     var name=document.getElementsByName('name')[0].value;
//
//   if(password1.length<5)
//   {
//     alert("Password must have at least 5 characters.");
//     return false;
//   }
//   if(name.length<2)
//   {
//     alert("Name must have at least 2 characters.");
//     return false;
//   }
//
//   if(username.length<5)
//   {
//     alert("Username must have at least 5 characters.");
//     return false;
//   }
//
//   var genderOptions = document.getElementsByName('gender');
//   var genderChecked = false;
//
//   for (var i = 0; i < genderOptions.length; i++) {
//       if (genderOptions[i].checked) {
//           genderChecked = true;
//           break;
//       }
//   }
//
//   if (!genderChecked) {
//       alert("Please select a gender.");
//       return false;
//   }
//
//     //prolazi form submission
//     return true;
// }
//
//
// $(function(){
//        $("#login_submit").click(function(){
//           if(!validateForm())
//             return;
//             var formData = {
//               name: $('#name').val(),
//               username: $('#username').val(),
//               password: $('#password').val(),
//               gender: $('input[name="gender"]:checked').val()
//           };
//
//           $.post('check_register.php', formData, function(response) {
//            if(response=="success")
//            {
//               window.location.href = 'redirected.php';
//            }
//            else
//            {
//             alert("Username already exists. Please choose a different username.")
//            }
//         });
//        })
// })
