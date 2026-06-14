<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

$host     = 'localhost';
$dbname   = 'queue_and_appointment_management';
$username = 'root';
$password = '';

$response = ['success' => false, 'message' => ''];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Unauthorized access. Please login first.';
        echo json_encode($response);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $response['message'] = 'Invalid request method.';
        echo json_encode($response);
        exit();
    }

    $matrix_number = trim($_POST['matrix_number'] ?? '');
    $student_name  = trim($_POST['student_name']  ?? '');
    $schedule_time = trim($_POST['schedule_time'] ?? '');
    $service       = trim($_POST['service']       ?? '');
    $notes         = trim($_POST['notes']         ?? '');
    $staff_id      = $_SESSION['user_id'];

    $errors = [];

    if (empty($matrix_number)) {
        $errors[] = 'Matrix number is required.';
    } elseif (!preg_match('/^[A-Za-z]{2}[0-9]{6}$/', $matrix_number)) {
        $errors[] = 'Invalid matrix number format. Use format: AI210234';
    }

    if (empty($student_name)) {
        $errors[] = 'Student name is required.';
    } elseif (strlen($student_name) < 3) {
        $errors[] = 'Student name must be at least 3 characters.';
    }

    if (empty($schedule_time)) {
        $errors[] = 'Appointment date and time is required.';
    }

    $valid_services = ['General Consultation', 'Follow-up Check', 'Vaccination', 'Prescription Refill'];
    if (empty($service)) {
        $errors[] = 'Service type is required.';
    } elseif (!in_array($service, $valid_services)) {
        $errors[] = 'Invalid service type selected.';
    }

    if (strlen($notes) > 500) {
        $errors[] = 'Notes must not exceed 500 characters.';
    }

    if (!empty($errors)) {
        $response['message'] = implode(' ', $errors);
        echo json_encode($response);
        exit();
    }

    $check_stmt = $pdo->prepare("SELECT id FROM appointments WHERE matrix_number=:m AND schedule_time=:s AND status!='Cancelled'");
    $check_stmt->execute([':m' => strtoupper($matrix_number), ':s' => $schedule_time]);
    if ($check_stmt->rowCount() > 0) {
        $response['message'] = 'This student already has an appointment at this time.';
        echo json_encode($response);
        exit();
    }

    $slot_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments WHERE schedule_time=:s AND status!='Cancelled'");
    $slot_stmt->execute([':s' => $schedule_time]);
    $slot_result = $slot_stmt->fetch(PDO::FETCH_ASSOC);
    if ($slot_result['count'] >= 5) {
        $response['message'] = 'This time slot is fully booked. Please select another time.';
        echo json_encode($response);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO appointments (matrix_number, student_name, schedule_time, service_type, notes, status, created_by, created_at) VALUES (:m, :sn, :st, :sv, :n, 'Pending', :cb, NOW())");
    $result = $stmt->execute([
        ':m'  => strtoupper($matrix_number),
        ':sn' => $student_name,
        ':st' => $schedule_time,
        ':sv' => $service,
        ':n'  => $notes,
        ':cb' => $staff_id
    ]);

    if ($result) {
        $response['success']        = true;
        $response['message']        = "Appointment booked successfully! Appointment ID: #" . $pdo->lastInsertId();
        $response['appointment_id'] = $pdo->lastInsertId();
    } else {
        $response['message'] = 'Failed to book appointment. Please try again.';
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $response['message'] = 'Database error occurred.';
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    $response['message'] = 'An unexpected error occurred.';
}

echo json_encode($response);
?>