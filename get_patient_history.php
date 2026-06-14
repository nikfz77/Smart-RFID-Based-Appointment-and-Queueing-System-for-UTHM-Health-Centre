<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$matrix_number = strtoupper(trim($_GET['matrix_number'] ?? ''));
$response      = ['success' => false, 'history' => []];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT queue_number, service_type, queue_status, created_at
        FROM queue
        WHERE matrix_number = :matrix_number
        ORDER BY created_at DESC
        LIMIT 20
    ");
    $stmt->execute([':matrix_number' => $matrix_number]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success'] = true;
    $response['history'] = $history;

} catch (PDOException $e) {
    error_log("DB Error in get_patient_history.php: " . $e->getMessage());
    $response['message'] = 'Database error.';
}

echo json_encode($response);
?>