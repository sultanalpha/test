function coreSetLang(isEnglish) {
  $(document).ready(function () {
    if (isEnglish) {
      coreSetLangLocal("/test/core/core_local/core-en_US.json");
      localStorage.setItem("local", "EN");
    } else {
      coreSetLangLocal("/test/core/core_local/core-ar_YE.json");
      localStorage.setItem("local", "AR");
    }
  });
}