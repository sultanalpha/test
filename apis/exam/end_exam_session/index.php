<?php
session_start();

unset($_SESSION['question_session']);
http_response_code(200);
echo json_encode(array("Status"=> "Session destoryed"));

?>