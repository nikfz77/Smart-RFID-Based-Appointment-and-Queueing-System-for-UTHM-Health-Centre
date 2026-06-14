<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$input    = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $appt_id   = intval($input['appointment_id'] ?? 0);
    $newStatus = trim($input['status'] ?? '');
    $valid     = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];

    if (!$appt_id || !in_array($newStatus, $valid)) {
        $response['message'] = 'Invalid request.';
        echo json_encode($response);
        exit();
    }

    $pdo->prepare("UPDATE appointments SET status=:status, updated_at=NOW() WHERE id=:id")
        ->execute([':status' => $newStatus, ':id' => $appt_id]);

    $pdo->prepare("INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at) VALUES (:uid, :uname, :role, 'Update Appointment', :details, :ip, NOW())")
        ->execute([
            ':uid'     => $_SESSION['user_id'],
            ':uname'   => $_SESSION['username'],
            ':role'    => $_SESSION['role'],
            ':details' => "Appointment ID $appt_id set to $newStatus",
            ':ip'      => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);

    $response['success'] = true;
    $response['message'] = "Appointment updated to $newStatus.";

} catch (PDOException $e) {
    error_log("DB Error in update_appointment.php: " . $e->getMessage());
    $response['message'] = 'Database error occurred.';
}

echo json_encode($response);
?>