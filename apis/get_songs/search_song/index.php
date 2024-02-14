<?php
include "../../connect.php";
include_once "../../jwt/validate-jwt.php";

$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);
$query = $data['query'] ?? null;

if (isset($_SERVER['HTTP_AUTH'])) {
    if ($query != null) {
        $jwt = $_SERVER['HTTP_AUTH'];
        $data = json_decode(is_jwt_valid($jwt));
        if (checkCode($data)) {
            $stmt = $con->prepare("SELECT * FROM songinfo2 WHERE song_name LIKE '%$query%'");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($data) {
                echo_json("200", "Success", "Vaild request", array("Data", $data));
            } else {
                echo_json_error(400, "Error", "772012x", "No data was found");
            }
        }
    } else {
        echo_json_error(400, "Error", "772011x", "Query token cant be empty");
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}
