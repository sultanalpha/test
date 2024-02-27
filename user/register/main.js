var isPasswordShow = false;
var isConfirmPasswordShow = false;

$(document).ready(function () {
  var isEnglish = checkLanguage();

  isEnglish
    ? $(".body-content .register-form .input-data label").css({
        left: "13%",
        right: "",
      })
    : $(".body-content .register-form .input-data label").css({
        right: "13%",
        left: "",
      });

  $("#local-lang-change-btn").click(function () {
    if (isEnglish) {
      setLang("local/ar_YE.json");
      coreSetLang(false);
      $("body").attr("dir", "rtl");
      localStorage.setItem("local", "AR");
      $(".body-content .register-form .input-data label").css({
        right: "13%",
        left: "",
      });
    } else {
      setLang("local/en_US.json");
      coreSetLang(true);
      $("body").attr("dir", "ltr");
      localStorage.setItem("local", "EN");
      $(".body-content .register-form .input-data label").css({
        left: "13%",
        right: "",
      });
    }
    isEnglish = !isEnglish;
  });

  $("#register-btn").click(async function (e) {
    e.preventDefault();
    $("#loading-screen").css("display", "grid");
    var email = $("#email-placeholder").val();
    var username = $("#username-placeholder").val();
    var password = $("#password-placeholder").val();
    var confirm_password = $("#confirm_password-placeholder").val();
    var csrf_token = $("#csrf-token").val();

    await registerRequest(
      email,
      username,
      password,
      confirm_password,
      null,
      csrf_token
    );
    $("#loading-screen").css("display", "none");
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
