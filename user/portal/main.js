var notVisibile = true;
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

  $("#login-btn").click(function (e) {
    e.preventDefault();
    var email = $("#email-placeholder").val();
    var password = $("#password-placeholder").val();
    var csrf_token = $("#csrf-token").val();
    loginRequest(email, password, isValidEmail(email), csrf_token);
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
