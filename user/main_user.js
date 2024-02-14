$(document).ready(function () {
  if (localStorage.getItem["token"] != null) {
      window.location = "/test/user/dashboard/";
  } else {
    //   window.location = "/test/";
  }
});
