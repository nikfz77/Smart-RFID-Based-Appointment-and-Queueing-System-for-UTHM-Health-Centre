<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false]);
    exit();
}

$host    = 'localhost';
$dbname  = 'queue_and_appointment_management';
$db_user = 'root';
$db_pass = '';

// Get last seen timestamp from request (frontend sends it)
$last_checked = $_GET['last_checked'] ?? date('Y-m-d H:i:s', strtotime('-1 minute'));

$response = ['success' => false, 'notifications' => [], 'count' => 0];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get new walk-ins since last check
    $stmt = $pdo->prepare("
        SELECT q.id, q.queue_number, q.matrix_number, q.service_type, q.created_at,
               s.full_name as student_name
        FROM queue q
        LEFT JOIN students s ON q.matrix_number = s.matrix_number
        WHERE q.created_at > :last_checked
          AND q.queue_status = 'Waiting'
          AND DATE(q.created_at) = CURDATE()
        ORDER BY q.created_at DESC
    ");
    $stmt->execute([':last_checked' => $last_checked]);
    $new_walkins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total waiting right now
    $waiting_count = $pdo->query("SELECT COUNT(*) FROM queue WHERE queue_status = 'Waiting' AND DATE(created_at) = CURDATE()")->fetchColumn();

    $notifications = array_map(function($w) {
        return [
            'id'           => $w['id'],
            'queue_number' => $w['queue_number'],
            'name'         => $w['student_name'] ?? $w['matrix_number'],
            'service'      => $w['service_type'],
            'time'         => date('h:i A', strtotime($w['created_at'])),
            'message'      => "New walk-in: {$w['queue_number']} — " . ($w['student_name'] ?? $w['matrix_number'])
        ];
    }, $new_walkins);

    $response['success']       = true;
    $response['notifications'] = $notifications;
    $response['count']         = count($notifications);
    $response['waiting_total'] = $waiting_count;
    $response['server_time']   = date('Y-m-d H:i:s');

} catch (PDOException $e) {
    error_log("DB Error in get_notifications.php: " . $e->getMessage());
}

echo json_encode($response);
?>