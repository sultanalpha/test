async function loginRequest(username, password, isEmail, csrf_token) {
  await $.ajax({
    type: "GET",
    url: "/test/apis/auth/login/get_public_token/",
    headers: {
      "X-CSRFToken": csrf_token,
    },
    success: async function (response) {
      let respond = JSON.parse(response);
      console.log(respond["public_token"]);
      try {
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(respond["public_token"]);
        var encrypted = encrypt.encrypt(password);
        // console.log(encrypted);
        await SendLoginRequest(encrypted, csrf_token, username, isEmail);
      } catch (error) {
        console.error("Encryption Error: ", error);
      }
    },
  });
}

async function SendLoginRequest(encryptedData, csrf_token, username, isEmail) {
  let encryptedDataJson = JSON.stringify({
    enc_: encryptedData,
    username: isEmail ? "" : username,
    email: isEmail ? username : "",
  });

  await $.ajax({
    type: "POST",
    url: "/test/apis/auth/login/",
    data: encryptedDataJson,
    headers: {
      "X-CSRFToken": csrf_token,
    },
    success: function (response, textStatus, jqXHR) {
      var statusCode = jqXHR.status;
      var respond = JSON.parse(jqXHR.responseText);
      if (statusCode == 200) {
        $("#server_txt").show();
        $("#server_txt").css("color", "green");
        $("#server_txt").text(respond["Message"]);
        if (respond["token"] != null) {
          localStorage.setItem("token", respond["token"]);
          window.location = "../../";
        } else {
          console.log("Something went wrong");
        }
      } else {
        console.log("Something went wrong");
      }
    },
    error: function (response, textStatus, jqXHR) {
      $("#server_txt").show();
      $("#server_txt").text(JSON.parse(response.responseText)["Message"]);
    },
  });
}
