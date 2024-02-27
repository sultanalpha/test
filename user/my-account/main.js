$(document).ready(function () {
  $("#user-info").hide();
  var isEnglish = checkLanguage();

  setUserInfo();
  connectedDeivces();

  // getUserInfo();
});

function setUserInfo() {
  let userEmail = localStorage.getItem("useremail");
  let userName = localStorage.getItem("username");
  $("#user-email").text(userEmail);
  $("#user-name").text(userName);
}

function connectedDeivces() {
  var csrf_token = $("#csrf-token").val();
  var token = localStorage.getItem("token");

  $.ajax({
    type: "GET",
    url: "/test/apis/users/connected_devices/",
    headers: {
      Auth: token,
      "X-Csrftoken": csrf_token,
    },
    success: function (response) {
      let respond = JSON.parse(response);
      if (respond["Code"] == 200 && respond["Status"] == "Success") {
        let devices = respond["devices_info"];
        for (let i = 0; i < devices.length; i++) {
          if (devices[i]["refresh_id"] == respond["Currect device session"]) {
            var temp = devices[0];
            devices[0] = devices[i];
            devices[i] = temp;
          }
        }
        console.log(devices);

        devices.forEach((e) => {
          let deviceType = "device-type";
          if (respond["Currect device session"] == e["refresh_id"]) {
            deviceType = "device-type device-current-session";
          }
          $("#devices-section #devices-content").append(
            '<div class="device-item">\
          <img src="/test/icons/device_type/' +
              e["device_type"] +
              '-50.png">\
          <div class="device-item-info">\
            <p class="' +
              deviceType +
              '">' +
              e["device_type"] +
              '</p>\
            <p class="device-brand">' +
              e["device_brand"] +
              '</p>\
            <p class="device-model">' +
              e["device_model"] +
              "</p>\
          </div>\
        </div>"
          );
        });
      }
    },
  });
}
