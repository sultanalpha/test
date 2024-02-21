<?php
$configargs = array(
    "config" => "C:/xampp/php/extras/openssl/openssl.cnf",
    'private_key_bits' => 2048,
    'default_md' => "sha256",
);

// 4096

// Create the private and public key

$res = openssl_pkey_new($configargs);
// Get private key
openssl_pkey_export($res, $privKey, NULL, $configargs);

// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

$data = 'plaintext data goes here';

// Encrypt the data to $encrypted using the public key
openssl_public_encrypt($data, $encrypted, $pubKey);

// Decrypt the data using the private key and store the results in $decrypted
openssl_private_decrypt($encrypted, $decrypted, $privKey);

echo "Private Key:<br>" . $encrypted . "<br>";
echo "Public Key:<br>" . $decrypted . "<br>";

// echo $decrypted;
