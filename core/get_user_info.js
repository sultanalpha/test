$(document).ready(function () {
  var tries = 0;

  function getUserInfo() {
    var csrf_token = $("#csrf-token").val();
    var token = localStorage.getItem("token");
    if (token !== null) {
      $.ajax({
        type: "GET",
        url: "/test/apis/users/user_info/",
        headers: {
          Auth: token,
          "X-Csrftoken": csrf_token,
        },
        success: function (response) {
          var respond = JSON.parse(response);
          if (respond["Code"] == 200 && respond["Status"] == "Success") {
            var userInfo = JSON.parse(JSON.stringify(respond["user_info"]));
            $("#user-info").show();
            $("#not-logged").hide();
            $("#login_txt").text(userInfo["users_name"]);
            $("#username").text(userInfo["users_name"]);
            localStorage.setItem("username", userInfo["users_name"]);
            $("#email").text(userInfo["users_email"]);
            localStorage.setItem("useremail", userInfo["users_email"]);
            $("#created").text(userInfo["created_time"]);
            localStorage.setItem("createdtime", userInfo["created_time"]);
            $("#login_button").attr("href", "/test/user/dashboard");
            $("#user-icon-btn").attr("href", "/test/user/dashboard");
            $("#user-avatar").attr("src", "/test/icons/" + userInfo['users_avatar']);
            $("#my-account-user-avatar").attr("src", "/test/icons/" + userInfo['users_avatar']);
            localStorage.setItem("usersavatar", userInfo["users_avatar"]);

            
            // Add username to "My account" page input with special ID. 
            $("#username-field").val(userInfo["users_name"]);
          }
        },
        error: function (response, textStatus, jqXHR) {
          var respondData = JSON.parse(response.responseText);
          if (
            (response.status = 401 && respondData["Error code"] == "772001x")
          ) {
            var publicToken = respondData["Public token"];
            var jsonData = JSON.stringify({
              token: token,
              public_token: publicToken,
            });

            if (tries < 5) {
              $.ajax({
                type: "post",
                url: "/test/apis/auth/update_tk/",
                data: jsonData,
                success: function (response, textStatus, jqXHR) {
                  var respondData = JSON.parse(jqXHR.responseText);
                  if (
                    jqXHR.status == 200 &&
                    respondData["Status"] == "Success"
                  ) {
                    localStorage.setItem("token", respondData["token"]);
                    tries++;
                    getUserInfo();
                    getTests();
                    connectedDeivces();
                  } else {
                    alert("Something went wrong");
                    localStorage.removeItem("token");
                    localStorage.removeItem("username");
                    localStorage.removeItem("useremail");
                    localStorage.removeItem("createdtime");
                    window.location = "../../";
                  }
                },
              });
            }
          }
        },
      });
    }
  }
  getUserInfo();
});
