<?php
include "../connect.php";
include_once "../jwt/validate-jwt.php";


if (isset($_SERVER['HTTP_AUTH'])) {
    $jwt = $_SERVER['HTTP_AUTH'];
    $data = json_decode(is_jwt_valid($jwt));
    if (checkCode($data)) {
        if (isset($_GET['query'])) {
            $songID = $_GET['query'];
            get_songs($songID);
        } else {
            get_songs();
        }
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}

function get_songs($songID = null)
{
    global $con;

    // $stmt = $con->prepare("SELECT * FROM songs, artist WHERE songs.artist_id = artist.artist_id;");
    if ($songID != null) {
        $stmt = $con->prepare("SELECT * FROM songinfo2 WHERE song_id = $songID");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            echo_json("200", "Success", "Vaild request", array("Data", $data));
        } else {
            echo_json_error(400, "Error", "772012x", "No data was found");
        }
    } else {
        $stmt = $con->prepare("SELECT * FROM songinfo2");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            echo_json("200", "Success", "Vaild request", array("Data", $data));
        } else {
            echo_json_error(400, "Error", "772012x", "No data was found");
        }
    }
}   
