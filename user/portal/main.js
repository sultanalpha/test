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
    if(isValidEmail(email)) {
      loginRequest(email, password, true, csrf_token);
    } else {
      loginRequest(email, password, false, csrf_token);
    }
  });
});


function isValidEmail(email) {
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  
  return emailPattern.test(email);
}