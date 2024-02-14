<?php
function checkCSRF($received_csrf_token = null)
{
    session_start();

    $csrf_token = $_SESSION['csrf_token'] ?? null;

    if ($csrf_token === null) {
        echo_json(400, "Invalid request", "Failed to create csrf token please refresh the site and try again");
        return false;
    } else {
        // $received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
        if ($received_csrf_token !== null) {
            if ($csrf_token !== $received_csrf_token) {
                echo_json(400, "Invalid request", "Invalid csrf token provided!");
                return false;
            } else {
                return true;
            }
        } else {
            echo_json(400, "Invalid request", "Csrf token cant be empty");
            return false;
        }
    }
}
