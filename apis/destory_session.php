<?php
session_start();
session_destroy();

http_response_code(200);
echo json_encode(array("Status"=> "Session destoryed"));

?>
