function coreSetLang(isEnglish) {
  $(document).ready(function () {
    if (isEnglish) {
      coreSetLangLocal("/test/core/core_local/core-en_US.json");
      localStorage.setItem("local", "EN");
      $(".body-content #login-form .input-password img, .body-content .input-password img, .body-content .input-confirmpassword img").css({
        "right": "12%",
        "left": ""
      });
    } else {
      coreSetLangLocal("/test/core/core_local/core-ar_YE.json");
      localStorage.setItem("local", "AR");
      $(".body-content #login-form .input-password img, .body-content .input-password img, .body-content .input-confirmpassword img").css({
        "left": "12%",
        "right": ""
      });
    }
  });
}