<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Doctor') {
    echo json_encode(['success' => false]);
    exit();
}
$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->prepare("UPDATE users SET last_active=NOW() WHERE id=:id")
        ->execute([':id' => $_SESSION['user_id']]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
?>