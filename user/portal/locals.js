function setLang(lang_path) {
  $.getJSON(lang_path, function (data) {
    $("#login_text").text(data["Login-text"]);
    $("#login-btn_txt").text(data["Login-btn-text"]);
    // $("#email-placeholder").attr("placeholder", data["Email-placeholder"]);
    $("#email-label").text(data["Email-placeholder"]);
    // $("#password-placeholder").attr("placeholder", data["Password-placeholder"]);
    $("#password-label").text(data["Password-placeholder"]);
  }).fail(function (error) {
    console.error("Error:", error);
  });
}
