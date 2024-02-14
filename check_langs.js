function checkLanguage() {
  var local = localStorage.getItem("local");
  if (local != null) {
    if (local == "EN") {
      setLang("local/en_US.json");
      coreSetLang(true);
      $("body").attr("dir", "ltr");

      $("#navigation-drawer").css({
        left: "-1000px",
        right: "",
      });

      return true;
    } else if (local == "AR") {
      setLang("local/ar_YE.json");
      coreSetLang(false);
      $("body").attr("dir", "rtl");

      $("#navigation-drawer").css({
        right: "-1000px",
        left: "",
      });

      return false;
    } else {
      console.log(
        "Something went wrong while setting the language so we setted the default language"
      );
      setLang("local/en_US.json");
      coreSetLang(true);
      $("body").attr("dir", "ltr");

      $("#navigation-drawer").css({
        left: "-1000px",
        right: "",
      });

      return true;
    }
  } else {
    setLang("local/en_US.json");
    coreSetLang(true);
    $("body").attr("dir", "ltr");

    $("#navigation-drawer").css({
      left: "-1000px",
      right: "",
    });

    return true;
  }
}
