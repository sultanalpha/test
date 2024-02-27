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
  <script src="../../libraries/jquery_3.7.1.js"></script>
  <script src='main.js'></script>
</head>

<body>
  <?php
  $server_root = $_SERVER['DOCUMENT_ROOT'];
  include("$server_root/test/bootstrap/navigation_drawer/navigation_drawer.php");
  include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
  include("$server_root/test/bootstrap/top_content/top_content.html");
  ?>

  <div class="bodyContent">
    <!-- <h2 id="user-email"> Here user email displayed </h2> -->
    <!-- <h2 id="user-name"> Here user email displayed </h2> -->
    <div class="user-avatar">
      <img src="" alt="" id="my-account-user-avatar" class="my-account-user-avatar">
      <img src="/test/icons/icons8-edit-30.png" alt="" id="my-account-user-edit" class="my-account-user-edit">
    </div>
    <div class="username-section section">
      <h4>Username: </h4>
      <div class="username-content content">
        <input type="text" id="username-field" disabled>
        <div class="change-username">
          <button>Change username</button>
        </div>
      </div>

    </div>
    <div class="email-section section">
      <h4>Email: </h4>
      <div class="email-content content">
        <p id="user-email" style="margin: 10px;"></p>
        <div class="change-email">
          <button>Change email</button>
          <button>Add backup email</button>
        </div>
      </div>
    </div>

    <div class="devices-section section" id="devices-section">
      <h4>Connected devices: </h4>
      <div class="devices-content content" id="devices-content">

      </div>
    </div>

    <div class="settings-section section">
      <h4>Settings: </h4>
      <div class="settings-content content">
        <button style="color: var(--buttonColor) !important;">Change password</button>
        <button>Logout</button>
        <button>Logout from all devices</button>
        <button>Delete account</button>

      </div>
    </div>


    <h2>Change password: </h2>
    <input type="password" placeholder="New password" id="new-password">
    <input type="password" placeholder="Confirm new password" id="confirm-new-password">
  </div>
</body>
<script src="../../locals.js"></script>
<script src="../../check_langs.js"></script>

</html>