<?php
include "../../connect.php";

if (isset($_GET['answer']) && isset($_GET['test_id']) && isset($_GET['question_id'])) {
    $answer = $_GET['answer'];
    $test_id = $_GET['test_id'];
    $question_id = $_GET['question_id'];

    if ($answer == "1" || $answer == "0") {
        $sql = "SELECT * FROM questions WHERE question_id = ? AND test_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $question_id,
            $test_id
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            if ($answer == $data['question_answer']) {
                echo_json(200, "Success", "Vaild answer");
            } else {
                echo_json(200, "Success", "Invalid answer");
            }
        } else {
        }
    } else {
        echo_json(400, "Bad request", "Invalid answer provided");

    }
}
