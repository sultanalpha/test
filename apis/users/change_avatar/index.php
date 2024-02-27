<?php
// Check if a file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];

    $server_root = $_SERVER['DOCUMENT_ROOT'];
    include("$server_root/test/user/avatars/");

    $filename = uniqid() . '_' . $file['name'];

    $uploadPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo 'Image uploaded!';
    } else {
        echo 'Error uploading image.';
    }
} else {
    echo 'No image uploaded.';
}
