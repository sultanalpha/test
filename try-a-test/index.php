<?php
session_start();
$isLoggedin = $_SESSION['isLoggedin'] ?? false;
if (!$isLoggedin) {
  session_destroy();
}

// if (isset($_GET['test_id'])) {
//   $test_id = $_GET['test_id'];
// }
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Try a test</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="locals.js"></script>
  <script src="../libraries/jquery_3.7.1.js"></script>

  <script src="../check_langs.js"></script>
  <script src="main.js"></script>
</head>

<body>
  <?php
  $server_root = $_SERVER['DOCUMENT_ROOT'];
  include("$server_root/test/bootstrap/navigation_drawer/navigation_drawer.php");
  include("$server_root/test/bootstrap/bottom_btns/bottom_btns.html");
  include("$server_root/test/bootstrap/top_content/top_content.html");
  ?>

  <div class="body-content" style="margin-top: 50px;">
    <div class="send-req" id="send-req">
      <h1 id="try-a-test"></h1>
      <h3 id="enter-code_txt">Enter test code below:</h3>
      <input type="text" id="test-id" placeholder="su_XXXXXX" />
      <button id="submit-btn">Submit</button>
    </div>
    <div class="test-content" id="test-content">
      <h2 id="question-counter"></h2>
      <!-- This is where the question will be displayed -->
      <br>
      <label id="question-content" class="question-content"></label>
      <br>
      <br>
      <label id="true-text"></label>
      <input type="radio" class="radio-buttons" id="is-true" value="Correct" name="answer">
      <br>
      <label id="false-text"></label>
      <input type="radio" class="radio-buttons" id="is-false" value="Wrong" name="answer">
      <br>
      <br>
      <button id="send-answer"></button>
      <button id="stop-test"></button>

    </div>
    <div class="result-content" id="result-content">
      <label id="result-txt"></label>
      <div class="results-details" id="results-details">
        <!-- <div class="result-item">
          <p class="result-item-txt">bjghjgh</p>
        </div> -->
      </div>
    </div>
  </div>

</body>

</html>