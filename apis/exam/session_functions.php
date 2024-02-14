<?php
session_start();
// if (isset($_SESSION['bruh'])) {
//     echo "BRUHHHHHHH";
// } else {
//     echo "nigga";
// }
function createSessionToken()
{
    if (isset($_SESSION['question_session'])) {
        return $_SESSION['question_session'];
    } else {
        $session_id = random_bytes(32);
        $session_id_hexString = bin2hex($session_id);
        $_SESSION['question_session'] = $session_id_hexString;
        return $_SESSION['question_session'];
    }
}

function checkSessionToken($newSessionToken)
{
    if (isset($_SESSION['question_session'])) {
        if ($newSessionToken === $_SESSION['question_session']) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
