function setLang(lang_path) {
    $.getJSON(lang_path, function (data) {
      $("#password-label").text(data["Password-placeholder"]);
    }).fail(function (error) {
      console.error("Error:", error);
    });
  }
  