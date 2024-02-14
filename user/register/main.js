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

    $.ajax({
      url: "https://api.ipify.org?format=json",
      method: "GET",
      dataType: "json",
      success: function (data) {
        const publicIP = data.ip;
        var csrf_token = $("#csrf-token").val();
        registerRequest(email, username, password, confirm_password, publicIP, csrf_token);
      },
      error: function (error) {
        reject(error);
      },
    });

  });
});
