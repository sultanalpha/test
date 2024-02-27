function setLang(lang_path) {
  $.getJSON(lang_path, function (data) {
    $("#register_text").text(data["register"]);

    // $("#email-placeholder").attr("placeholder", data["email"]);
    // $("#username-placeholder").attr("placeholder", data["username"]);
    // $("#password-placeholder").attr("placeholder", data["password"]);
    // $("#confirm_password-placeholder").attr(
    //   "placeholder",
    //   data["confirm-password"]
    // );
    $("#register-btn_txt").text(data["register"]);
    $("#email-label").text(data["email"]);
    $("#username-label").text(data["username"]);
    $("#password-label").text(data["password"]);
    $("#confirm-password-label").text(data["confirm-password"]);
  }).fail(function (error) {
    console.error("Error:", error);
  });
}
