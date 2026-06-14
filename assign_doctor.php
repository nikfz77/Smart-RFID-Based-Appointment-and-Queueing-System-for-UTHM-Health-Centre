<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

$host    = 'localhost';
$dbname  = 'queue_and_appointment_management';
$db_user = 'root';
$db_pass = '';

$data      = json_decode(file_get_contents('php://input'), true);
$queue_id  = intval($data['queue_id']  ?? 0);
$doctor_id = intval($data['doctor_id'] ?? 0);
$room      = trim($data['room']        ?? '');

if (!$queue_id || !$doctor_id || !$room) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verify doctor is available
    $doc = $pdo->prepare("SELECT full_name, room, is_available FROM users WHERE id = :id AND role = 'Doctor'");
    $doc->execute([':id' => $doctor_id]);
    $doctor = $doc->fetch(PDO::FETCH_ASSOC);

    if (!$doctor || !$doctor['is_available']) {
        echo json_encode(['success' => false, 'message' => 'Doctor is not available']);
        exit();
    }

    // Get queue info
    $q = $pdo->prepare("SELECT queue_number, matrix_number FROM queue WHERE id = :id");
    $q->execute([':id' => $queue_id]);
    $queue = $q->fetch(PDO::FETCH_ASSOC);

    if (!$queue) {
        echo json_encode(['success' => false, 'message' => 'Queue not found']);
        exit();
    }

    // Assign doctor and room
    $pdo->prepare("
        UPDATE queue SET assigned_doctor_id = :did, assigned_room = :room, updated_at = NOW()
        WHERE id = :id
    ")->execute([':did' => $doctor_id, ':room' => $room, ':id' => $queue_id]);

    // Log
    $pdo->prepare("
        INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
        VALUES (:uid, :un, :role, 'Assign Doctor', :det, :ip, NOW())
    ")->execute([
        ':uid'  => $_SESSION['user_id'],
        ':un'   => $_SESSION['username'],
        ':role' => $_SESSION['role'],
        ':det'  => "Queue {$queue['queue_number']} assigned to {$doctor['full_name']} in $room",
        ':ip'   => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
    ]);

    echo json_encode(['success' => true, 'message' => "Patient assigned to {$doctor['full_name']} in $room"]);

} catch (PDOException $e) {
    error_log("DB Error in assign_doctor.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}
?>