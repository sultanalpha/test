$(document).ready(function () {
    var tries = 0;
  
    function getUserInfo() {
      var csrf_token = $("#csrf-token").val();
      var token = localStorage.getItem("token");
      if (token !== null) {
        $.ajax({
          type: "GET",
          url: "/test/apis/user_info/",
          headers: {
            Auth: token,
            "X-Csrftoken": csrf_token
          },
          success: function (response) {
            var respond = JSON.parse(response);
            if (respond["Code"] == 200 && respond["Status"] == "Success") {
              var userInfo = JSON.parse(JSON.stringify(respond["user_info"]));
              $("#user-info").show();
              $("#not-logged").hide();
              $("#login_txt").text(userInfo["users_name"]);
              $("#username").text(userInfo["users_name"]);
              $("#email").text(userInfo["users_email"]);
              $("#created").text(userInfo["created_time"]);
              $("#login_button").attr("href", "/test/user/dashboard");
              $("#user-icon-btn").attr("href", "/test/user/dashboard");
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
                    } else {
                      alert("Something went wrong");
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
  