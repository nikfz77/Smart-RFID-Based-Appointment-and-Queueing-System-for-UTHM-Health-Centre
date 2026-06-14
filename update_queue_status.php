<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$data     = json_decode(file_get_contents('php://input'), true);
$queue_id = intval($data['queue_id'] ?? 0);
$status   = trim($data['status']    ?? '');

$allowed = ['Waiting', 'Called', 'Being-Served', 'Not-Arrived', 'Completed', 'Cancelled'];
if (!$queue_id || !in_array($status, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");
    try { $pdo->exec("ALTER TABLE queue ADD COLUMN IF NOT EXISTS called_at DATETIME NULL DEFAULT NULL"); } catch (Exception $e) {}

    if ($status === 'Called') {
        $doc = $pdo->prepare("SELECT room FROM users WHERE id=:id");
        $doc->execute([':id' => $_SESSION['user_id']]);
        $doctor = $doc->fetch(PDO::FETCH_ASSOC);

        $pdo->prepare("
            UPDATE queue
            SET called_at          = NOW(),
                assigned_doctor_id = COALESCE(assigned_doctor_id, :did),
                assigned_room      = COALESCE(NULLIF(assigned_room,''), :room),
                updated_at         = NOW()
            WHERE id = :id AND queue_status = 'Waiting'
        ")->execute([':did' => $_SESSION['user_id'], ':room' => $doctor['room'] ?? '', ':id' => $queue_id]);
        $msg = 'Patient called — 5-minute arrival countdown started.';

    } elseif ($status === 'Being-Served') {
        $doc = $pdo->prepare("SELECT room FROM users WHERE id=:id");
        $doc->execute([':id' => $_SESSION['user_id']]);
        $doctor = $doc->fetch(PDO::FETCH_ASSOC);

        $pdo->prepare("
            UPDATE queue
            SET queue_status       = 'Being-Served',
                assigned_doctor_id = COALESCE(assigned_doctor_id, :did),
                assigned_room      = COALESCE(NULLIF(assigned_room,''), :room),
                updated_at         = NOW()
            WHERE id = :id
        ")->execute([':did' => $_SESSION['user_id'], ':room' => $doctor['room'] ?? '', ':id' => $queue_id]);
        $msg = 'Patient marked as arrived — Being-Served.';

    } elseif ($status === 'Not-Arrived') {
        $pdo->prepare("UPDATE queue SET queue_status='Cancelled', updated_at=NOW() WHERE id=:id")
            ->execute([':id' => $queue_id]);
        $msg = 'Patient did not arrive — queue entry cancelled.';

    } else {
        $pdo->prepare("UPDATE queue SET queue_status=:status, updated_at=NOW() WHERE id=:id")
            ->execute([':status' => $status, ':id' => $queue_id]);
        $msg = "Queue updated to $status.";
    }

    $pdo->prepare("INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at) VALUES (:uid, :un, :role, 'Update Queue Status', :det, :ip, NOW())")
        ->execute([
            ':uid'  => $_SESSION['user_id'],
            ':un'   => $_SESSION['username'],
            ':role' => $_SESSION['role'],
            ':det'  => "Queue #$queue_id → $status",
            ':ip'   => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);

    echo json_encode(['success' => true, 'message' => $msg]);

} catch (PDOException $e) {
    error_log("update_queue_status: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}
?>