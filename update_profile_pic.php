<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_pic VARCHAR(255) NULL DEFAULT NULL");

    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        exit();
    }

    $file    = $_FILES['photo'];
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit();
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File too large (max 2MB)']);
        exit();
    }

    $upload_dir = __DIR__ . '/uploads/profiles/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
    $filepath = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $old = $pdo->prepare("SELECT profile_pic FROM users WHERE id=:id");
        $old->execute([':id' => $_SESSION['user_id']]);
        $old_pic = $old->fetchColumn();
        if ($old_pic && file_exists(__DIR__ . '/uploads/profiles/' . $old_pic)) {
            unlink(__DIR__ . '/uploads/profiles/' . $old_pic);
        }

        $pdo->prepare("UPDATE users SET profile_pic=:pic WHERE id=:id")
            ->execute([':pic' => $filename, ':id' => $_SESSION['user_id']]);

        echo json_encode(['success' => true, 'message' => 'Photo updated!', 'filename' => $filename]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>