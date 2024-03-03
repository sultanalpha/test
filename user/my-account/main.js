$(document).ready(function () {
  $("#user-info").hide();
  let globalRefreshID;
  var isEnglish = checkLanguage();

  isEnglish
    ? $(".input-data label").css({
        left: "13%",
        right: "",
      })
    : $(".input-data label").css({
        right: "13%",
        left: "",
      });

  // Show custom modal
  $("#devices-content").on("click", ".logout-btn", function (e) {
    e.preventDefault();
    console.log("Clicked");
    var id = $(this).attr("id");
    globalRefreshID = id.split("-")[2];
    console.log(globalRefreshID);
    let currentDisplay = $("#custom-modal").css("display");
    currentDisplay == "none"
      ? $("#custom-modal").css("display", "flex")
      : $("#custom-modal").css("display", "none");
  });

  // Hide custom modal
  $("#close-modal").click(function (e) {
    e.preventDefault();
    $("#custom-modal").css("display", "none");
    globalRefreshID = undefined;
  });

  $("#logout-btn").click(function (e) {
    e.preventDefault();
    if (globalRefreshID != undefined) {
      var password = $("#password-placeholder").val();
      sendReq(globalRefreshID, password);
    }
  });

  setUserInfo();
  connectedDeivces();

  $("#logout-only").click(function (e) {
    e.preventDefault();
    logoutUser(true);
  });

  $("#logout-all").click(function (e) {
    e.preventDefault();
    logoutUser(false);
  });
  // getUserInfo();
});

function setUserInfo() {
  let userEmail = localStorage.getItem("useremail");
  let userName = localStorage.getItem("username");
  $("#user-email").text(userEmail);
  $("#user-name").text(userName);
}

function connectedDeivces() {
  $("#devices-section #devices-content").empty();
  let csrf_token = $("#csrf-token").val();
  let token = localStorage.getItem("token");

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
        if (devices.length > 1) {
          for (let i = 0; i < devices.length; i++) {
            if (devices[i]["refresh_id"] == respond["Currect device session"]) {
              var temp = devices[0];
              devices[0] = devices[i];
              devices[i] = temp;
            }
          }
        }
        console.log(devices);

        devices.forEach((e) => {
          let deviceType = "device-type";
          let logoutBtnDisplay = "block";
          if (respond["Currect device session"] == e["refresh_id"]) {
            deviceType = "device-type device-current-session";
            logoutBtnDisplay = "none";
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
          <div class='logout-btn' id='logout-btn-" +
              e["refresh_id"] +
              "' style='display: " +
              logoutBtnDisplay +
              "'><p>Logout</p></div>\
        </div>"
          );
        });
      }
    },
    error: function (response, textStatus, jqXHR) {
      var respondData = JSON.parse(response.responseText);
      if (respondData["Error code"] == "772009x") {
        localStorage.removeItem("token");
        localStorage.removeItem("username");
        localStorage.removeItem("useremail");
        localStorage.removeItem("createdtime");
        localStorage.removeItem("usersavatar");
        window.location = "/test/";
      }
    },
  });
}
function logoutUser(onlyThisUser) {
  let csrf_token = $("#csrf-token").val();
  let token = localStorage.getItem("token");

  $.ajax({
    type: "GET",
    url: onlyThisUser
      ? "/test/apis/users/logout/logout_me/"
      : "/test/apis/users/logout/logout_all/",
    headers: {
      Auth: token,
      "X-Csrftoken": csrf_token,
    },
    success: function (response) {
      let respond = JSON.parse(response);
      if (respond["Code"] == 200 && respond["Status"] == "Success") {
        localStorage.removeItem("token");
        localStorage.removeItem("username");
        localStorage.removeItem("useremail");
        localStorage.removeItem("createdtime");
        localStorage.removeItem("usersavatar");
        window.location = "../../";
      }
    },
  });
}
