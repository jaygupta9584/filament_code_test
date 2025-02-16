<?php

function uploadImage($file, $uploadDir = 'uploads/') {
    
    $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'File upload error.'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return ['error' => 'File size exceeds 2MB limit.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!array_key_exists($mimeType, $allowedMimeTypes)) {
        return ['error' => 'Invalid file type.'];
    }

    $magicBytes = file_get_contents($file['tmp_name'], false, null, 0, 8);
    if (!validateMagicBytes($mimeType, $magicBytes)) {
        return ['error' => 'Invalid file content.'];
    }

    $extension = $allowedMimeTypes[$mimeType];
    $newFileName = uniqid('img_', true) . '.' . $extension;

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Prevent script execution in upload directory
    file_put_contents($uploadDir . '.htaccess', "Options -Indexes\n<Files *>\nDeny from all\n</Files>\n");

    // Move file securely
    $filePath = $uploadDir . $newFileName;
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['error' => 'Failed to move uploaded file.'];
    }

    return ['success' => 'File uploaded successfully.', 'file' => $filePath];
}

// Validate file signature (Basic Magic Bytes Check)
function validateMagicBytes($mimeType, $magicBytes) {
    $magicSignatures = [
        'image/jpeg' => ["\xFF\xD8\xFF"], // JPEG
        'image/png'  => ["\x89\x50\x4E\x47"], // PNG
        'image/gif'  => ["\x47\x49\x46\x38"], // GIF
        'image/webp' => ["\x52\x49\x46\x46"] // WEBP
    ];

    if (!isset($magicSignatures[$mimeType])) {
        return false;
    }

    foreach ($magicSignatures[$mimeType] as $signature) {
        if (strpos($magicBytes, $signature) === 0) {
            return true;
        }
    }
    return false;
}
// print_r($_FILES);
// exit();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = uploadImage($_FILES['image']);
    echo json_encode($result);
}


?>