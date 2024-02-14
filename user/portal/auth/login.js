function loginRequest(username, password, isEmail, csrf_token) {
  var json;
  if (isEmail) {
    json = JSON.stringify({
      email: username,
      password: password,
    });
  } else {
    json = JSON.stringify({
      username: username,
      password: password,
    });
  }

  console.log(json);

  $.ajax({
    type: "POST",
    url: "/test/apis/auth/login/",
    data: json,
    headers: {
      "X-CSRFToken": csrf_token,
    },
    success: function (response, textStatus, jqXHR) {
      var statusCode = jqXHR.status;
      var response = JSON.parse(jqXHR.responseText);
      if (statusCode == 200) {
        $("#server_txt").text(response["Message"]);
        if (response["token"] != null) {
          localStorage.setItem("token", response["token"]);
          window.location = "../../";
        } else {
          console.log("Something went wrong");
        }
      } else {
        console.log("Something went wrong");
      }
    },
    error: function (response, textStatus, jqXHR) {
      $("#server_txt").text(JSON.parse(response.responseText)["Message"]);
    },
  });
}
