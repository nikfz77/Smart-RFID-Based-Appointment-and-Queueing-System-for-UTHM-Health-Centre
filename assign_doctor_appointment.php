<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$data      = json_decode(file_get_contents('php://input'), true);
$appt_id   = intval($data['appointment_id'] ?? 0);
$doctor_id = intval($data['doctor_id']      ?? 0);
$room      = trim($data['room']             ?? '');

if (!$appt_id || !$doctor_id || !$room) {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $doc = $pdo->prepare("SELECT full_name, room, is_available FROM users WHERE id=:id AND role='Doctor'");
    $doc->execute([':id' => $doctor_id]);
    $doctor = $doc->fetch(PDO::FETCH_ASSOC);

    if (!$doctor || !$doctor['is_available']) {
        echo json_encode(['success' => false, 'message' => 'Doctor is not available']);
        exit();
    }

    $pdo->exec("ALTER TABLE appointments ADD COLUMN IF NOT EXISTS assigned_doctor_id INT NULL");
    $pdo->exec("ALTER TABLE appointments ADD COLUMN IF NOT EXISTS assigned_room VARCHAR(20) NULL");

    $pdo->prepare("UPDATE appointments SET assigned_doctor_id=:did, assigned_room=:room, updated_at=NOW() WHERE id=:id")
        ->execute([':did' => $doctor_id, ':room' => $room, ':id' => $appt_id]);

    $pdo->prepare("
        INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
        VALUES (:uid, :un, :role, 'Assign Doctor to Appointment', :det, :ip, NOW())
    ")->execute([
        ':uid'  => $_SESSION['user_id'],
        ':un'   => $_SESSION['username'],
        ':role' => $_SESSION['role'],
        ':det'  => "Appointment #$appt_id assigned to {$doctor['full_name']} in $room",
        ':ip'   => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
    ]);

    echo json_encode(['success' => true, 'message' => "Assigned to {$doctor['full_name']} in $room"]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
}
?>