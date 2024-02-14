$(document).ready(function () {
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
});
