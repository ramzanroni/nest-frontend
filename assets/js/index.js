function userLogin() {
    var email = $("#email").val();
    var password = $("#password").val();
    var check = "userLogin";
    $.ajax({
        url: "pages/userAction.php",
        type: "POST",
        data: {
            email: email,
            password: password,
            check: check
        },
        success: function (response) {
            console.log(response);
        }
    });
}

function userRegister()
{
    var username = $("#emaiusernamel").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var confirmPassword = $("#confirmPassword").val();
    var agreeBtn = $("#agreeBtn").val();
    var check = "userRegistration";
    $.ajax({
        url: "pages/userAction.php",
        type: "POST",
        data: {
            email: email,
            password: password,
            check: check
        },
        success: function (response) {
            console.log(response);
        }
    });
}
