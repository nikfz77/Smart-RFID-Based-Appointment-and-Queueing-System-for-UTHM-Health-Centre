<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");

    try { $pdo->exec("ALTER TABLE queue ADD COLUMN IF NOT EXISTS called_at DATETIME NULL DEFAULT NULL"); } catch (Exception $e) {}

    $stmt = $pdo->query("
        SELECT q.id, q.queue_number, q.matrix_number, q.service_type, q.queue_status,
               q.assigned_room, q.assigned_doctor_id, q.scheduled_time, q.called_at, q.created_at,
               s.full_name as student_name,
               u.full_name as doctor_name
        FROM queue q
        LEFT JOIN students s ON q.matrix_number = s.matrix_number
        LEFT JOIN users u ON q.assigned_doctor_id = u.id
        WHERE DATE(q.created_at) = CURDATE()
          AND q.queue_status NOT IN ('Cancelled')
        ORDER BY q.created_at ASC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>