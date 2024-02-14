<?php
session_start();
$isLoggedin = $_SESSION['isLoggedin'] ?? false;
if (!$isLoggedin) {
  session_destroy();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Your account</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
  <script src='main.js'></script>
  <script src="../../libraries/jquery_3.7.1.js"></script>
</head>

<body>
  <?php
  $server_root = $_SERVER['DOCUMENT_ROOT'];
  include("$server_root/test/bootstrap/navigation_drawer/navigation_drawer.php");
  include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
  include("$server_root/test/bootstrap/top_content/top_content.html");
  ?>
</body>
<script src="../../locals.js"></script>
<script src="../../check_langs.js"></script>

</html>