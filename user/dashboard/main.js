$(document).ready(function () {
  var tries = 0;

  $("#create-test").click(function (e) {
    e.preventDefault();
    console.log("clicked");
    $("#create-test-body").css("display", "flex");
    $("#create-test-body").animate({
      top: "0",
      opacity: "1",
    });
  });
  $("#create-test-body").click(function (e) {
    e.preventDefault();
    $("#create-test-body").animate(
      {
        top: "0",
        opacity: "0",
      },
      function () {
        $("#create-test-body").css({
          display: "none",
          top: "-250px",
        });
      }
    );
  });
  getTests();
});

function getTests() {
  var csrf_token = $("#csrf-token").val();
  let token = localStorage.getItem("token");
  $.ajax({
    type: "GET",
    headers: {
      Auth: token,
      "X-Csrftoken": csrf_token,
    },
    url: "/test/apis/exam/get_own_exam/",
    success: function (response) {
      let respond = JSON.parse(response);
      if (respond["Code"] == 200 && respond["Status"] == "Success") {
        let test_info = respond["test_info"];
        test_info.forEach((e) => {
          $("#owned-tests").append(
            '<div class="test-item">\
        <h3 class="text-details">' +
              e["test_name"] +
              '</h3>\
        <p class="text-details">' +
              e["test_desc"] +
              '</p>\
        <p class="text-details">' +
              e["test_link"] +
              '</p>\
        <p class="text-details">' +
              e["test_created"] +
              "</p>\
    </div>"
          );
        });
      }
    },
  });
}
