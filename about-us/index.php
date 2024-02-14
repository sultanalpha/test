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
    <title>About us</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src="../libraries/jquery_3.7.1.js"></script>
    <script src="../locals.js"></script>
    <script src="../check_langs.js"></script>
    <script src='main.js'></script>
</head>

<body>
    <?php
    $server_root = $_SERVER['DOCUMENT_ROOT'];
    include("$server_root/test/bootstrap/navigation_drawer/navigation_drawer.php");
    include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
    include("$server_root/test/bootstrap/top_content/top_content.html");
    ?>
    <div class="body-content">
        <div class="About us"><h1>About us</h1></div>
        <h4>SultanKingGD single fullstack devloper</h4>
        <div class="About us"><h1>Who am I?</h1></div>
        <p>I am a single fullstack devloper</p>
        <p>My name is Sultan also called as "SultanKingGD"</p>
        <p>I started working on this site in 2023 november to improve my coding skills</p>
        <br><p>Something you didnt know? here with every answer with details <a href="#">Help me</a></p>
        <p>Email: sultanalpha82@gmail.com</p>
        <p>Phone number: +967772796226</p>
        <br><br><p>Copyrights revered to SultanKingGD</p>

    </div>
</body>

</html>