function setLang(lang_path) {
  $.getJSON(lang_path, function (data) {
    $("#try-a-test_txt").text(data["Try-a-test"]);
    $("#home_txt").text(data["Home"]);
    $("#about-us_txt").text(data["About-us"]);
    if(localStorage.getItem("token") == null) {
      $("#login_txt").text(data["Login"]);
    }
    $("#welcome_txt").text(data["Welcome-msg"]);
    $("#test_txt").text(data["Sample-test"]);
    $("#username_txt").text(data["user-name"]);
    $("#email_txt").text(data["email"]);
    $("#created_txt").text(data["Created-time"]);
    $("#notlogged_txt").text(data["Not-logged"]);
  }).fail(function (error) {
    console.error("Error:", error);
  });
}