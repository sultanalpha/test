function setLang(lang_path) {
  $.getJSON(lang_path, function (data) {
    $("#try-a-test").text(data["try-a-test"]);
    $("#enter-code_txt").text(data["test-code"]);
    $("#submit-btn").text(data["submit"]);
    $("#true-text").text(data["correct"]);
    $("#false-text").text(data["wrong"]);
    $("#send-answer").text(data["send-answer"]);
    $("#stop-test").text(data["stop-test"]);
  }).fail(function (error) {
    console.error("Error:", error);
  });
}
