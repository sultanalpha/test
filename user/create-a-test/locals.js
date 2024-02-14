function setLang(lang_path) {
    $.getJSON(lang_path, function (data) {

    }).fail(function (error) {
      console.error("Error:", error);
    });
  }