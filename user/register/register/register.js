function registerRequest(
  email,
  username,
  password,
  confirm_password,
  publicIP,
  csrf_token
) {
  var json = JSON.stringify({
    username: username,
    password: password,
    email: email,
    confirm_password: confirm_password,
    public_ip: publicIP,
  });
  $.ajax({
    type: "POST",
    url: "/test/apis/auth/signup/",
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
      $("#server_txt").text(response["Message"]);
    },
    error: function (response, textStatus, jqXHR) {
      $("#server_txt").text(JSON.parse(response.responseText)["Message"]);
    },
  });
}
