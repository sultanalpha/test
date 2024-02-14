<?php
// include '../jwt/check-csrf.php';
include '../../connect.php';
include '../session_functions.php';

// $received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
// if (!checkCSRF($received_csrf_token)) {
//     return;
// }


// This function is used to check the token provided from the request
// and return true if it is correct syntax.
function checkSyntax($string)
{
    $pattern = '/^su_[a-zA-Z0-9]{6}$/';

    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}


$rawData = file_get_contents('php://input');
$dataJson = json_decode($rawData, true);
$test_link = $dataJson['test_token'] ?? null;


if ($test_link === null) {
    echo_json(400, "Bad request", "Test id can't be empty");
    return;
}

if (checkSyntax($test_link)) {

    // Create a token and save it in a session for the user


    $sql = "SELECT * FROM test WHERE test_link = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([
        $test_link
    ]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($count > 0) {
        $test_id = $data['test_id'];
        $question_sql = "SELECT question_id, question_title, test_id FROM questions WHERE test_id = ?";
        $question_stmt = $con->prepare($question_sql);
        $question_stmt->execute([
            $test_id
        ]);
        $question_data = $question_stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($question_data) {
            echo_json(200, "Success", "Successfully got questions.", array("data", $question_data), createSessionToken(), "Session_id");
        } else {
            echo_json(400, "Bad request", "No questions loaded");
        }
    } else {
        echo_json(400, "Bad request", "Invalid token provided");
        return;
    }
} else {
    echo_json(400, "Bad request", "Invalid token provided");
}
