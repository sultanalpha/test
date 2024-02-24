var isPasswordShow = false;
var isConfirmPasswordShow = false;

$(document).ready(function () {
  var isEnglish = checkLanguage();

  $("#local-lang-change-btn").click(function () {
    if (isEnglish) {
      setLang("local/ar_YE.json");
      coreSetLang(false);
      $("body").attr("dir", "rtl");
      localStorage.setItem("local", "AR");
    } else {
      setLang("local/en_US.json");
      coreSetLang(true);
      $("body").attr("dir", "ltr");
      localStorage.setItem("local", "EN");
    }
    isEnglish = !isEnglish;
  });

  $("#register-btn").click(function (e) {
    e.preventDefault();
    var email = $("#email-placeholder").val();
    var username = $("#username-placeholder").val();
    var password = $("#password-placeholder").val();
    var confirm_password = $("#confirm_password-placeholder").val();
    var csrf_token = $("#csrf-token").val();

    registerRequest(
      email,
      username,
      password,
      confirm_password,
      null,
      csrf_token
    );
    // $.ajax({
    //   url: "https://api.ipify.org?format=json",
    //   method: "GET",
    //   dataType: "json",
    //   success: function (data) {
    //     const publicIP = data.ip;
    //   },
    //   error: function (error) {
    //     reject(error);
    //   },
    // });
  });

  $("#password-visibility").click(function (e) {
    console.log("clicked");
    e.preventDefault();
    if (!isPasswordShow) {
      changeVisibility(
        this,
        "/test/icons/password/icons8-show-password-24.png",
        "text",
        "#password-placeholder"
      );
    } else {
      changeVisibility(
        this,
        "/test/icons/password/icons8-hide-password-24.png",
        "password",
        "#password-placeholder"
      );
    }
    isPasswordShow = !isPasswordShow;
  });

  $("#confirm-password-visibility").click(function (e) {
    console.log("clicked");
    e.preventDefault();
    if (!isConfirmPasswordShow) {
      changeVisibility(
        this,
        "/test/icons/password/icons8-show-password-24.png",
        "text",
        "#confirm_password-placeholder"
      );
    } else {
      changeVisibility(
        this,
        "/test/icons/password/icons8-hide-password-24.png",
        "password",
        "#confirm_password-placeholder"
      );
    }
    isConfirmPasswordShow = !isConfirmPasswordShow;
  });
});

function changeVisibility(selector, imagePath, type, inputID) {
  $(selector).attr({
    src: imagePath,
  });

  $(inputID).attr("type", type);
}
