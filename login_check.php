<?php
// login_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: staff_login.php');
    exit();
}

// Auto-logout disabled — session stays active indefinitely

$current_page = basename($_SERVER['PHP_SELF']);
$user_role    = $_SESSION['role'] ?? 'Staff';

$doctor_pages = ['doctor_dashboard.php'];
$admin_pages  = [
    'admin_dashboard.php','admin_staff_management.php',
    'admin_logs.php','admin_reports.php','admin_student_list.php',
    'admin_patient_list.php',
];

if (in_array($current_page, $admin_pages) && $user_role !== 'Admin') {
    header('Location: staff_dashboard.php?error=unauthorized');
    exit();
}

if (in_array($current_page, $doctor_pages) && $user_role !== 'Doctor') {
    header('Location: staff_dashboard.php?error=unauthorized');
    exit();
}

function logActivity($pdo, $action, $details = '') {
    try {
        $pdo->prepare("
            INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
            VALUES (:uid, :un, :role, :action, :details, :ip, NOW())
        ")->execute([
            ':uid'     => $_SESSION['user_id']  ?? 0,
            ':un'      => $_SESSION['username']  ?? 'unknown',
            ':role'    => $_SESSION['role']      ?? 'Staff',
            ':action'  => $action,
            ':details' => $details,
            ':ip'      => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    } catch (Exception $e) {
        error_log("Log Error: " . $e->getMessage());
    }
}
?>