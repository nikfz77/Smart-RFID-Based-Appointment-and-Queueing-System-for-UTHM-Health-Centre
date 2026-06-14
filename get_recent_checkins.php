<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([]);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $username='root'; $password='';
$response = ['success' => false, 'checkins' => []];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $today = date('Y-m-d');
    $stmt  = $pdo->prepare("
        SELECT q.queue_number, s.full_name as student_name, q.created_at
        FROM queue q
        LEFT JOIN students s ON q.matrix_number = s.matrix_number
        WHERE DATE(q.created_at) = :today
        ORDER BY q.created_at DESC
        LIMIT 10
    ");
    $stmt->execute(['today' => $today]);
    $checkins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success']  = true;
    $response['checkins'] = $checkins;

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $response['message'] = 'Database error.';
}

echo json_encode($response);
?>