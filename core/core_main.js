function coreSetLang(isEnglish) {
  $(document).ready(function () {
    if (isEnglish) {
      coreSetLangLocal("/test/core/core_local/core-en_US.json");
      localStorage.setItem("local", "EN");
      $("#login-form .input-password img, .input-password img, .input-confirmpassword img").css({
        "right": "12%",
        "left": ""
      });
    } else {
      coreSetLangLocal("/test/core/core_local/core-ar_YE.json");
      localStorage.setItem("local", "AR");
      $("#login-form .input-password img, .input-password img, .input-confirmpassword img").css({
        "left": "12%",
        "right": ""
      });
    }
  });
}