<?php
include "../../connect.php";
require_once '../../getID3-master/getid3/getid3.php';
include_once "../../jwt/validate-jwt.php";


if (isset($_SERVER['HTTP_AUTH'])) {
    $jwt = $_SERVER['HTTP_AUTH'];
    $data = json_decode(is_jwt_valid($jwt));
    if (checkCode($data)) {
        loadSong();
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}


function getMP3Duration($filePath)
{
    $getID3 = new getID3();
    $fileInfo = $getID3->analyze($filePath);

    if (isset($fileInfo['playtime_string'])) {
        return $fileInfo['playtime_string'];
    }

    return null;
}

function loadSong()
{
    global $con;
    if (isset($_GET['song_id'])) {
        $stmt = $con->prepare("SELECT * FROM songinfo2 WHERE song_id = ?");
        $stmt->execute([
            $_GET["song_id"]
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $count  = $stmt->rowCount();
        if ($count > 0) {
            $songPath = "../songs/" . $data['song_path'];
            if (file_exists($songPath)) {
                $duration = getMP3Duration($songPath);

                if ($duration) {
                    $fileContent = file_get_contents($songPath);
                    $fileSize = filesize($songPath);

                    header('Content-Type: audio/mpeg');
                    header('Content-Length: ' . $fileSize);
                    header('Content-Disposition: inline; filename="' . basename($songPath) . '"');
                    header('X-Duration: ' . $duration);

                    echo $fileContent;
                } else {
                    echo_json_error(501, "Server error", "772003x", "Something went wrong!");
                }
            } else {
                echo_json_error(404, "Failed", "772020x", "File not found!");
            }
        } else {
            echo_json_error(400, "Invalid request", "772011x", "This request is not valid");
        }
    } else {
        echo_json_error(400, "Invalid request", "772011x", "This request is not valid");
        // echo_json(400, "Invalid request", "This request is not valid");
    }
}
