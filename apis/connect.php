<?php
date_default_timezone_set('Asia/Kuwait');

$dsn = "mysql:host=localhost;dbname=sultan";
$pass = "Sultan@20020408";
$user = "root";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"

);
$countrowinpage = 9;
try {
    include "functions.php";
    $con = new PDO($dsn, $user, $pass, $option);
    define("SECRET", "wZN6PSK2ANMw0q1EYmoB+ktnN+QuRdZc3ETPmHJfiz1i0St3Ml7vo+ZkW90TO/sCkva4ciBWjmRwt/JmOvZ2mtQOTsJ69RJJ3Z7tSiBZ2nU=");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    if (!isset($notAuth)) {
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
