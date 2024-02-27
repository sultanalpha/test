$(document).ready(function () {
  $("#user-info").hide();
  var isEnglish = checkLanguage();

  $("#local-lang-change-btn").click(async function () {
    $("#loading-screen").css("display", "grid");
    if (isEnglish) {
      await setLang("local/ar_YE.json");
      await coreSetLang(false);
      $("body").attr("dir", "rtl");
      localStorage.setItem("local", "AR");
      $("#navigation-drawer").css({
        right: "-1000px",
        left: "",
      });

      $("#copyright-txt").css({
        right: "10px",
        left: "",
      });
    } else {
      await setLang("local/en_US.json");
      await coreSetLang(true);
      $("body").attr("dir", "ltr");
      localStorage.setItem("local", "EN");
      $("#navigation-drawer").css({
        left: "-1000px",
        right: "",
      });

      $("#copyright-txt").css({
        left: "10px",
        right: "",
      });
    }
    isEnglish = !isEnglish;
    $("#loading-screen").css("display", "none");
  });

  $("#navigation-drawer-btn").click(function () {
    var local = localStorage.getItem("local");
    if (local == "EN") {
      $("#navigation-drawer").animate({ left: "0px" });
    } else if (local == "AR") {
      $("#navigation-drawer").animate({ right: "0px" });
    } else {
      $("#navigation-drawer").animate({ left: "0px" });
    }
  });

  $("#navigation-empty").click(function (e) {
    var local = localStorage.getItem("local");
    if (local == "EN") {
      $("#navigation-drawer").animate({ left: "-1000px" });
    } else if (local == "AR") {
      $("#navigation-drawer").animate({ right: "-1000px" });
    } else {
      $("#navigation-drawer").animate({ left: "-1000px" });
    }
  });
});
