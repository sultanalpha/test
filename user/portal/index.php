<?php
session_start();
$csrf_token_var = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token_var;
$_SESSION['isLoggedin'] = false;
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Login to SultanKingGD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="../../libraries/jquery_3.7.1.js"></script>
  <script src="locals.js"></script>
  <script src="main.js"></script>
  <script src="auth/login.js"></script>
</head>

<body>

  <?php
  $server_root = $_SERVER['DOCUMENT_ROOT'];
  include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
  ?>
  <div class="body-content" style="margin-top: 50px; padding: 10px 0px;">
    <h1 id="login_text"></h1>
    <!-- <form id="login-form"> -->
    <div id="login-form">
      <div class="input-email input-data">
        <input type="text" id="email-placeholder" placeholder="">
      </div>
      <div class="input-password input-data">
        <input type="password" id="password-placeholder" placeholder="">
        <img src="/test/icons/password/icons8-hide-password-24.png" height="24" width="24" id="password-visibility">
      </div>
      <input type="hidden" id="csrf-token" value="<?php echo $csrf_token_var ?>">
      <p id="server_txt"></p>
      <button id="login-btn">
        <p id="login-btn_txt"></p>
      </button>
      <p>Dont have account? <a href="../register/">Register now</a></p>
    </div>
    <!-- </form> -->
  </div>

</body>

</html>