async function sendReq(refreshID, password) {
  await $.ajax({
    type: "GET",
    url: "/test/apis/auth/login/get_public_token/",
    // headers: {
    //   "X-CSRFToken": csrf_token,
    // },
    success: async function (response) {
      let respond = JSON.parse(response);
      console.log(respond["public_token"]);
      try {
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(respond["public_token"]);
        var encrypted = encrypt.encrypt(password);
        console.log(encrypted);
        await logoutDevice(encrypted, refreshID);
      } catch (error) {
        console.error("Encryption Error: ", error);
      }
    },
  });
}

async function logoutDevice(encrypted, refreshID) {
  let csrf_token = $("#csrf-token").val();
  let token = localStorage.getItem("token");
  let json = JSON.stringify({
    enc_: encrypted,
    refresh_id: refreshID,
  });
  await $.ajax({
    type: "POST",
    url: "/test/apis/users/logout/logout_device/",
    headers: {
      Auth: token,
      "X-Csrftoken": csrf_token,
    },
    data: json,
    success: function (response) {
      let respond = JSON.parse(response);
      if (respond["Code"] == 200 && respond["Status"] == "Success") {
        $("#custom-modal").css("display", "none");
        globalRefreshID = undefined;
        
        connectedDeivces();
      }
    },
  });
}
