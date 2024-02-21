<?php
session_start();
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
    echo json_encode(array("Status" => "Success", "public_token" => $pubKey));
}

generatePublicToken($configargs);
