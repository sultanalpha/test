function loginRequest(username, password, isEmail, csrf_token) {
  let json = isEmail
    ? JSON.stringify({
        email: username,
        password: password,
      })
    : JSON.stringify({
        username: username,
        password: password,
      });

  $.ajax({
    type: "GET",
    url: "/test/apis/auth/login/get_public_token/",
    success: function (response) {
      let respond = JSON.parse(response);
      console.log(respond["public_token"]);
      try {
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(respond["public_token"]);
        var encrypted = encrypt.encrypt(json);
        // console.log(encrypted);
        encrypt_2(encrypted, csrf_token);
      } catch (error) {
        console.error("Encryption Error: ", error);
      }
    },
  });
}

function encrypt_2(encryptedData, csrf_token) {
  let encryptedDataJson = JSON.stringify({
    enc_: encryptedData,
  });

  console.log(encryptedDataJson);

  $.ajax({
    type: "POST",
    url: "/test/apis/auth/login/",
    data: encryptedDataJson,
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
