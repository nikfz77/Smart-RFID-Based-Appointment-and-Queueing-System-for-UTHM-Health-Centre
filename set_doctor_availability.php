<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'Doctor') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$data         = json_decode(file_get_contents('php://input'), true);
$room         = trim($data['room'] ?? '');
$is_available = intval($data['is_available'] ?? 0);
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->prepare("
        UPDATE users
        SET room          = :r,
            is_available  = :a,
            clocked_in_at = IF(:a2=1 AND is_available=0, NOW(), clocked_in_at),
            last_active   = NOW(),
            updated_at    = NOW()
        WHERE id = :id
    ")->execute([':r' => $room ?: null, ':a' => $is_available, ':a2' => $is_available, ':id' => $_SESSION['user_id']]);

    $pdo->prepare("
        INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
        VALUES (:uid, :un, 'Doctor', :act, :det, :ip, NOW())
    ")->execute([
        ':uid' => $_SESSION['user_id'],
        ':un'  => $_SESSION['username'],
        ':act' => $is_available ? 'Clock In' : 'Clock Out',
        ':det' => $is_available ? "Available in $room" : 'Set unavailable',
        ':ip'  => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
    ]);

    echo json_encode([
        'success' => true,
        'message' => $is_available ? "You are now available in $room" : 'You are now unavailable'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB error']);
}
?>