<?php
session_start();
$csrf_token_var = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token_var;
$_SESSION['isLoggedin'] = false;
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Register</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="../../libraries/jquery_3.7.1.js"></script>
  <script src="locals.js"></script>
  <script src="main.js"></script>
  <script src="./register/register.js"></script>
</head>

<body>
  <?php
  $server_root = $_SERVER['DOCUMENT_ROOT'];
  include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
  ?>
  <div class="body-content">
    <h1 id="register_text"></h1>
    <!-- <form id="register-form"> -->
    <div class="input-email input-data">
      <input type="email" id="email-placeholder" placeholder="">
    </div>
    <div class="input-username input-data">
      <input type="username" id="username-placeholder" placeholder="">
    </div>
    <div class="input-password input-data">
      <input type="password" id="password-placeholder" placeholder="">
        <img src="/test/icons/password/icons8-hide-password-24.png" height="24" width="24" id="password-visibility">
    </div>
    <div class="input-confirmpassword input-data">
      <input type="password" id="confirm_password-placeholder" placeholder="">
        <img src="/test/icons/password/icons8-hide-password-24.png" height="24" width="24" id="confirm-password-visibility">
    </div>
    <input type="hidden" id="csrf-token" value="<?php echo $csrf_token_var ?>">
    <p id="server_txt"></p>
    <button id="register-btn">
      <p id="register-btn_txt"></p>
    </button>
    <p>Already have account? <a href="../portal/">Login now</a></p>
    <!-- </form> -->
  </div>

</body>

</html>