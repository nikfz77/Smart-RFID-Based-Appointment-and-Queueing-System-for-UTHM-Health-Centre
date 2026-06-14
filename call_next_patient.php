<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$host     = 'localhost';
$dbname   = 'queue_and_appointment_management';
$username = 'root';
$password = '';

$response = ['success' => false, 'message' => ''];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("UPDATE queue SET queue_status='Completed', updated_at=NOW() WHERE queue_status='Being-Served'");

    $next_stmt = $pdo->query("
        SELECT id, queue_number, matrix_number, service_type
        FROM queue
        WHERE queue_status='Waiting'
          AND DATE(created_at)=CURDATE()
        ORDER BY id ASC
        LIMIT 1
    ");
    $next_patient = $next_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$next_patient) {
        $response['message'] = 'No patients in the waiting queue.';
        echo json_encode($response);
        exit();
    }

    $pdo->prepare("UPDATE queue SET queue_status='Being-Served', updated_at=NOW() WHERE id=:id")
        ->execute([':id' => $next_patient['id']]);

    $name_stmt = $pdo->prepare("SELECT full_name FROM students WHERE matrix_number=:m LIMIT 1");
    $name_stmt->execute([':matrix_number' => $next_patient['matrix_number']]);
    $student      = $name_stmt->fetch(PDO::FETCH_ASSOC);
    $student_name = $student ? $student['full_name'] : $next_patient['matrix_number'];

    $response['success']      = true;
    $response['message']      = "Now serving: {$next_patient['queue_number']} - {$student_name}";
    $response['queue_number'] = $next_patient['queue_number'];
    $response['matrix_number']= $next_patient['matrix_number'];
    $response['student_name'] = $student_name;
    $response['service']      = $next_patient['service_type'];

} catch (PDOException $e) {
    error_log("DB Error in call_next_patient.php: " . $e->getMessage());
    $response['message'] = 'Database error occurred.';
} catch (Exception $e) {
    error_log("Error in call_next_patient.php: " . $e->getMessage());
    $response['message'] = 'An unexpected error occurred.';
}

echo json_encode($response);
?>