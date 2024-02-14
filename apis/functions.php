<?php

define("MB", 1048576);

function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function checkCode($data)
{
    if ($data->status) {
        // echo $data->message;
        return true;
    } else {
        http_response_code(400);
        if ($data->code == 400) {
            http_response_code(401);
            echo_json_error($data->http_code, "Error", "772001x", "Token expired", true, $data->message);
        } else if ($data->code == 401) {
            echo_json_error($data->http_code, "Error", "772002x", $data->message, true);
        } else if ($data->code == 402) {
            echo_json_error($data->http_code, "Error", "772009x", $data->message, true);
        } else {
            http_response_code($data->http_code);
            echo_json_error($data->http_code, "Error", "772003x", "Something went wrong!", true);
        }
        return false;
    }
}

function echo_json($code, $status, $message, $data = null, $moreData = null, $infoName = "Info")
{
    http_response_code($code);
    if ($data == null) {
        echo json_encode(array("Code" => $code, "Status" => $status, "Message" => $message, $infoName => $moreData));
    } else {
        echo json_encode(array("Code" => $code, "Status" => $status, "Message" => $message, $data[0] => $data[1], $infoName => $moreData));
    }
}

function echo_json_error($code, $status, $error_code, $message, $hide_http_code = false, $public_token = null)
{
    if (!$hide_http_code) {
        http_response_code($code);
    }
    if ($public_token != null) {
        echo json_encode(array("Code" => $code, "Status" => $status, "Error code" => $error_code, "Message" => $message, "Public token" => $public_token));
    } else {
        echo json_encode(array("Code" => $code, "Status" => $status, "Error code" => $error_code, "Message" => $message));
    }
}

function validateEmail($email)
{
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (preg_match($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}

function checkLength($data)
{
    if (strlen($data) >= 8 && strlen($data) < 32) {
        return true;
    } else {
        return false;
    }
}

function filterRequest($requestname)
{
    return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllData($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    if ($where == null) {
        $stmt = $con->prepare("SELECT  * FROM $table ");
    } else {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }

    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
        return $count;
    } else {
        if ($count > 0) {
            return array("status" => "success", "data" => $data);
        } else {
            return array("status" => "failure");
        }
    }
}

function getData_v1($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    } else {
        return $count;
    }
}

function getData($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            if ($table == "users") {
                $users_id = $data['users_id'];
                $getServicesData = getServices($users_id);
                $isService = $getServicesData[0];
                $serviceData = $getServicesData[1];
                echo json_encode(array("status" => "success", "data" => $data, "isService" => $isService, "service_data" => $serviceData));
            } else {
                echo json_encode(array("status" => "success", "data" => $data));
            }
        } else {
            echo json_encode(array("status" => "failure"));
        }
    } else {
        return $count;
    }
}
function getServices($id, $json = true)
{
    $returningData = array();
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM services WHERE users_id = '$id' ");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            array_push($returningData, true);
            array_push($returningData, $data);
            return $returningData;
        } else {
            array_push($returningData, false);
            array_push($returningData, Null);
            return $returningData;
        }
    } else {
        return $count;
    }
}

function insertData($table, $data, $json = true)
{
    global $con;
    foreach ($data as $field => $v)
        $ins[] = ':' . $field;
    $ins = implode(',', $ins);
    $fields = implode(',', array_keys($data));
    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);
    foreach ($data as $f => $v) {
        $stmt->bindValue(':' . $f, $v);
    }
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function insertDatav2($table, $data, $json = true)
{
    global $con;
    foreach ($data as $field => $v)
        $ins[] = ':' . $field;
    $ins = implode(',', $ins);
    $fields = implode(',', array_keys($data));
    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);
    foreach ($data as $f => $v) {
        $stmt->bindValue(':' . $f, $v);
    }
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}



function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function imageUpload($dir, $imageRequest)
{
    global $msgError;
    if (isset($_FILES[$imageRequest])) {
        $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
        $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
        $imagesize  = $_FILES[$imageRequest]['size'];
        $allowExt   = array("jpg", "jpeg", "JPEG", "png", "gif", "mp3", "pdf", "svg");
        $strToArray = explode(".", $imagename);
        $ext        = end($strToArray);
        $ext        = strtolower($ext);

        if (!empty($imagename) && !in_array($ext, $allowExt)) {
            $msgError = "EXT";
        }
        if ($imagesize > 2 * MB) {
            $msgError = "size";
        }
        if (empty($msgError)) {
            move_uploaded_file($imagetmp,  $dir . "/" . $imagename);
            return $imagename;
        } else {
            return "fail";
        }
    } else {
        return 'empty';
    }
}



function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "wael" ||  $_SERVER['PHP_AUTH_PW'] != "wael12345") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }
    // End 
}
function printFailure($message = "none")
{
    echo json_encode(array("status" => "failure", "message" => $message));
}
function printSuccess($message = "none")
{
    echo json_encode(array("status" => "success", "message" => $message));
}
function result($count)
{
    if ($count > 0) {
        printSuccess();
    } else {
        printFailure();
    }
}

function sendEmail($to, $title, $body)
{ // لجل ارسال الايميل
    $header = "From: support@magdyalariki.com" . "\n" . "CC: magdyalariki77@gmail.com ";
    mail($to, $title, $body, $header);
}


// دالة الاشعارات
function sendGCM($title, $message, $topic, $pageid, $pagename)
{


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
        "to" => '/topics/' . $topic,
        'priority' => 'high',
        'content_available' => true,

        'notification' => array(
            "body" =>  $message,
            "title" =>  $title,
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "sound" => "default"

        ),
        'data' => array(
            "pageid" => $pageid,
            "pagename" => $pagename
        )

    );


    $fields = json_encode($fields);
    $headers = array(
        'Authorization: key=' . "AAAAix7_T68:APA91bFF1kz54d1I0K8QZAGrRLpO_qcU-_kuE33VL2a8dcyeWZWGLYHFD4kGid5rij4ZbBdO1-J7gPnRjhZ9n4jBdglSOo7pwb11LJZNapgJ6KA2jeaDn3B8Rce8XsNR4cFdgwFPkehS",
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $result = curl_exec($ch);
    return $result;
    curl_close($ch);
}


function sendGCMV2($title, $message, $imageName, $topic, $pageid, $pagename, $productID)
{


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
        "to" => '/topics/' . $topic,
        'priority' => 'high',
        'content_available' => true,
        // 'notification' => array(
        //     "body" =>  $message,
        //     "title" =>  $title,
        //     "click_action" => "FLUTTER_NOTIFICATION_CLICK",
        //     "sound" => "default"
        // ),
        'data' => array(
            "notificationBody" =>  $message,
            "notificationTitle" =>  $title,
            "pageid" => $pageid,
            "pagename" => $pagename,
            "imageName" => $imageName,
            "productData" => $productID
        )

    );


    $fields = json_encode($fields);
    $headers = array(
        'Authorization: key=' . "AAAAix7_T68:APA91bFF1kz54d1I0K8QZAGrRLpO_qcU-_kuE33VL2a8dcyeWZWGLYHFD4kGid5rij4ZbBdO1-J7gPnRjhZ9n4jBdglSOo7pwb11LJZNapgJ6KA2jeaDn3B8Rce8XsNR4cFdgwFPkehS",
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $result = curl_exec($ch);
    return $result;
    curl_close($ch);
}


function insertNotify($title, $body, $userid, $serid, $sername, $topic, $pageid, $pagename)
{
    global $con;
    $stmt = $con->prepare("INSERT INTO `notification`(`notification_title`, `notification_body`, `notification_userid`, `notification_serid`, `notification_sername`) VALUES (? , ? , ? , ?, ?)");
    $stmt->execute(array($title, $body, $userid, $serid, $sername));
    sendGCM($title, $body, $topic, $pageid, $pagename);
    $count = $stmt->rowCount();
    return $count;
}
