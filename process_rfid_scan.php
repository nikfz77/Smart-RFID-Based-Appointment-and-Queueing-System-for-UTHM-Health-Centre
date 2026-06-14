<?php
header('Content-Type: application/json');
session_start();

// Database configuration
$host = 'localhost';
$dbname = ' queue_and_appointment_management';
$username = 'root';
$password = '';

$response = ['success' => false, 'message' => '', 'student' => null];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get request data
    $input = json_decode(file_get_contents('php://input'), true);
    $rfid_uid = isset($input['rfid_uid']) ? trim($input['rfid_uid']) : '';
    
    if (empty($rfid_uid)) {
        $response['message'] = 'RFID UID is required';
        echo json_encode($response);
        exit;
    }
    
    // For demo purposes, map some demo UIDs to matrix numbers
    $demo_mapping = [
        'A1B2C3D4' => 'AI210234',
        'B2C3D4E5' => 'BI200156',
        'C3D4E5F6' => 'AI220345',
        'D4E5F6G7' => 'CI200678',
        'E5F6G7H8' => 'DI210890',
        'F6G7H8I9' => 'EI200123'
    ];
    
    // Check if UID exists in demo mapping
    if (isset($demo_mapping[$rfid_uid])) {
        $matrix_number = $demo_mapping[$rfid_uid];
    } else {
        // Try to find in rfid_tags table if it exists
        try {
            $stmt = $pdo->prepare("SELECT matrix_number FROM rfid_tags WHERE rfid_uid = :rfid_uid AND status = 'Active'");
            $stmt->execute(['rfid_uid' => $rfid_uid]);
            $rfid_record = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($rfid_record) {
                $matrix_number = $rfid_record['matrix_number'];
            } else {
                $response['message'] = 'RFID card not registered or inactive';
                echo json_encode($response);
                exit;
            }
        } catch (PDOException $e) {
            // If rfid_tags table doesn't exist, use demo mapping only
            $response['message'] = 'RFID card not found in system';
            echo json_encode($response);
            exit;
        }
    }
    
    // Fetch student details
    $stmt = $pdo->prepare("
        SELECT 
            matrix_number,
            full_name,
            email,
            phone,
            faculty,
            program
        FROM students 
        WHERE matrix_number = :matrix_number
    ");
    
    $stmt->execute(['matrix_number' => $matrix_number]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        $response['success'] = true;
        $response['message'] = 'Student found';
        $response['student'] = $student;
    } else {
        $response['message'] = 'Student not found in database';
    }
    
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>