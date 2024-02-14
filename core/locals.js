function coreSetLangLocal(lang_path) {
  $.getJSON(lang_path, function (data) {
    $("#lang-type_txt").text(data["language-type"]);

    $("#username_txt").text(data["user-name"]);
    $("#email_txt").text(data["email"]);
    $("#created_txt").text(data["Created-time"]);
    $("#notlogged_txt").text(data["Not-logged"]);
    $("#home_txt").text(data["Home"]);
    $("#try-a-test_txt").text(data["Try-a-test"]);
    $("#about-us_txt").text(data["About-us"]);
    $("#my-account_txt").text(data["My-account"]);
  }).fail(function (error) {
    console.error("Error:", error);
  });
}
