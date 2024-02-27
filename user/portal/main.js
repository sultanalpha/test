var notVisibile = true;
$(document).ready(function () {
  var isEnglish = checkLanguage();

  isEnglish
    ? $(".body-content .login-form .input-data label").css({
        left: "13%",
        right: "",
      })
    : $(".body-content .login-form .input-data label").css({
        right: "13%",
        left: "",
      });

  $("#local-lang-change-btn").click(function () {
    if (isEnglish) {
      setLang("local/ar_YE.json");
      coreSetLang(false);
      $("body").attr("dir", "rtl");
      localStorage.setItem("local", "AR");
      $(".body-content .login-form .input-data label").css({
        right: "13%",
        left: "",
      });
    } else {
      setLang("local/en_US.json");
      coreSetLang(true);
      $("body").attr("dir", "ltr");
      localStorage.setItem("local", "EN");
      $(".body-content .login-form .input-data label").css({
        left: "13%",
        right: "",
      });
    }
    isEnglish = !isEnglish;
  });

  $("#login-btn").click(async function (e) {
    $("#server_txt").hide();
    $("#loading-screen").css("display", "grid");
    e.preventDefault();
    console.log("loading screen showed");
    var email = $("#email-placeholder").val();
    var password = $("#password-placeholder").val();
    var csrf_token = $("#csrf-token").val();
    await loginRequest(email, password, isValidEmail(email), csrf_token);
    $("#loading-screen").css("display", "none");
  });

  $("#password-visibility").click(function (e) {
    e.preventDefault();
    $("#password-placeholder").attr("type", notVisibile ? "text" : "password");
    $("#password-visibility").attr(
      "src",
      notVisibile
        ? "/test/icons/password/icons8-show-password-24.png"
        : "/test/icons/password/icons8-hide-password-24.png"
    );
    notVisibile = !notVisibile;
  });
});

function isValidEmail(email) {
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  return emailPattern.test(email);
}
