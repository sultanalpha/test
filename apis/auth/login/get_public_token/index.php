<?php
include "../../../jwt/check-csrf.php";
include "../../../connect.php";

$received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
if (!checkCSRF($received_csrf_token)) {
    return;
}

$configargs = array(
    "config" => "C:/xampp/php/extras/openssl/openssl.cnf",
    'private_key_bits' => 2048,
    'default_md' => "sha256",
);
function generatePublicToken($configargs)
{
    $res = openssl_pkey_new($configargs);
    openssl_pkey_export($res, $privKey, NULL, $configargs);

    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];
    $_SESSION['public_rsa_key'] = $pubKey;
    $_SESSION['private_rsa_key'] = $privKey;

    echo json_encode(array("Code" => 200, "Status" => "Success", "public_token" => $pubKey));
}

generatePublicToken($configargs);

// $configargs = array(
//     "config" => "C:/xampp/php/extras/openssl/openssl.cnf",
//     'private_key_bits' => 2048,
//     'default_md' => "sha256",
// );


// $res = openssl_pkey_new($configargs);

// openssl_pkey_export($res, $privKey, NULL, $configargs);

// $pubKey = openssl_pkey_get_details($res);
// $pubKey = $pubKey["key"];

// $data = 'plaintext data goes here';

// openssl_public_encrypt($data, $encrypted, $pubKey);

// openssl_private_decrypt($encrypted, $decrypted, $privKey);

// echo "Private Key:<br>" . $encrypted . "<br>";
// echo "Public Key:<br>" . $decrypted . "<br>";

// echo $decrypted;
