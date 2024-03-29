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
  <script src="../../libraries/jsencrypt.min.js"></script>
  <script src="locals.js"></script>
  <script src="main.js"></script>
  <script src="auth/login.js"></script>
</head>

<body>

  <?php
  $server_root = $_SERVER['DOCUMENT_ROOT'];
  include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
  include("$server_root/test/bootstrap/loading/loading.html");
  include("$server_root/test/bootstrap/custom_input/custom_input.php");
  ?>
  <div class="body-content" style="margin: 50px 0px; padding: 10px 0px;">
    <!-- <form id="login-form"> -->
    <div id="login-form" class="login-form">
      <h1 id="login_text"></h1>
      <?php
      setInput("email", "text");
      setInput("password", "password");
      ?>

      <input type="hidden" id="csrf-token" value="<?php echo $csrf_token_var ?>">
      <p id="server_txt" class="server_txt"></p>
      <button id="login-btn" class="login-btn">
        <p id="login-btn_txt" style="color: black;"></p>
      </button>
      <p>Dont have account? <a href="../register/" style="color: blue;">Register now</a></p>
    </div>
    <!-- </form> -->
  </div>

</body>

</html>