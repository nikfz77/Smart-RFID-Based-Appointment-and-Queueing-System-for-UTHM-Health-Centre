<?php
header('Content-Type: application/json');
session_start();

// Database configuration
$host = 'localhost';
$dbname = ' queue_and_appointment_management';
$username = 'root';
$password = '';

$response = [
    'success' => false, 
    'message' => '', 
    'queue_number' => null
];

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get JSON input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Extract and validate input
    $matrix_number = isset($input['matrix_number']) ? trim($input['matrix_number']) : '';
    $service_type = isset($input['service_type']) ? trim($input['service_type']) : '';
    
    // Validation
    if (empty($matrix_number)) {
        $response['message'] = 'Matrix number is required';
        echo json_encode($response);
        exit;
    }
    
    if (empty($service_type)) {
        $response['message'] = 'Service type is required';
        echo json_encode($response);
        exit;
    }
    
    // Verify student exists
    $stmt = $pdo->prepare("SELECT matrix_number FROM students WHERE matrix_number = :matrix_number");
    $stmt->execute(['matrix_number' => $matrix_number]);
    
    if (!$stmt->fetch()) {
        $response['message'] = 'Student not found in database';
        echo json_encode($response);
        exit;
    }
    
    // Check for duplicate check-in today
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("
        SELECT queue_number 
        FROM queue 
        WHERE matrix_number = :matrix_number 
        AND DATE(created_at) = :today
        AND queue_status IN ('Waiting', 'Being-Served')
    ");
    $stmt->execute([
        'matrix_number' => $matrix_number,
        'today' => $today
    ]);
    
    if ($existing = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $response['success'] = true;
        $response['message'] = 'Student already checked in today';
        $response['queue_number'] = $existing['queue_number'];
        echo json_encode($response);
        exit;
    }
    
    // Generate queue number for today
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM queue 
        WHERE DATE(created_at) = :today
    ");
    $stmt->execute(['today' => $today]);
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Generate new queue number (Q001, Q002, Q003, etc.)
    $queue_number = 'Q' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    
    // Insert into queue table
    $stmt = $pdo->prepare("
        INSERT INTO queue 
        (queue_number, matrix_number, service_type, queue_status, created_at) 
        VALUES 
        (:queue_number, :matrix_number, :service_type, 'Waiting', NOW())
    ");
    
    $stmt->execute([
        'queue_number' => $queue_number,
        'matrix_number' => $matrix_number,
        'service_type' => $service_type
    ]);
    
    // Check if insert was successful
    if ($stmt->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = 'Check-in successful! Queue number: ' . $queue_number;
        $response['queue_number'] = $queue_number;
        
        // Log the check-in
        error_log("RFID Check-in: " . $matrix_number . " - Queue: " . $queue_number);
    } else {
        $response['message'] = 'Failed to create queue entry';
    }
    
} catch (PDOException $e) {
    // Log database errors
    error_log("Database Error in process_rfid_checkin.php: " . $e->getMessage());
    $response['message'] = 'Database error: ' . $e->getMessage();
    
} catch (Exception $e) {
    // Log general errors
    error_log("Error in process_rfid_checkin.php: " . $e->getMessage());
    $response['message'] = 'System error: ' . $e->getMessage();
}

// Return JSON response
echo json_encode($response);
?>