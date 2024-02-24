function setLang(lang_path) {
  $.getJSON(lang_path, function (data) {
    $("#create-a-test").text(data["create-a-test"]);
    $("#your-tests").text(data["your-tests"]);
  }).fail(function (error) {
    console.error("Error:", error);
  });
}