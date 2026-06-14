<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$response = ['success' => false, 'message' => ''];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
        $response['message'] = 'Unauthorized.';
        echo json_encode($response);
        exit();
    }

    $data   = json_decode(file_get_contents('php://input'), true);
    $action = trim($data['action'] ?? '');
    $me     = intval($_SESSION['user_id']);

    // ── ADD STAFF ──
    if ($action === 'add') {
        $full_name = trim($data['full_name'] ?? '');
        $username  = trim($data['username']  ?? '');
        $password  = trim($data['password']  ?? '');
        $email     = strtolower(trim($data['email'] ?? ''));
        $role      = trim($data['role'] ?? 'Staff');

        if (empty($full_name) || empty($username) || empty($password) || empty($email)) {
            $response['message'] = 'All fields are required.';
            echo json_encode($response);
            exit();
        }
        if (strlen($password) < 6) {
            $response['message'] = 'Password must be at least 6 characters.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email address.';
            echo json_encode($response);
            exit();
        }
        if (!in_array($role, ['Admin', 'Staff', 'Doctor'])) {
            $response['message'] = 'Invalid role.';
            echo json_encode($response);
            exit();
        }

        $chk = $pdo->prepare("SELECT id FROM users WHERE username=:u");
        $chk->execute([':u' => $username]);
        if ($chk->rowCount() > 0) {
            $response['message'] = 'Username already exists.';
            echo json_encode($response);
            exit();
        }

        $chk2 = $pdo->prepare("SELECT id FROM users WHERE email=:e");
        $chk2->execute([':e' => $email]);
        if ($chk2->rowCount() > 0) {
            $response['message'] = 'Email already registered.';
            echo json_encode($response);
            exit();
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO users (username, password, full_name, email, role, status, created_at) VALUES (:u, :p, :n, :e, :r, 'Active', NOW())")
            ->execute([':u' => $username, ':p' => $hashed, ':n' => $full_name, ':e' => $email, ':r' => $role]);

        $pdo->prepare("INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at) VALUES (:uid, :un, 'Admin', 'Add Staff', :det, :ip, NOW())")
            ->execute([':uid' => $me, ':un' => $_SESSION['username'] ?? 'admin', ':det' => "Added: $username ($role) — $email", ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0']);

        $response['success'] = true;
        $response['message'] = "Staff '$username' added successfully!";
        echo json_encode($response);
        exit();
    }

    // ── TOGGLE STATUS ──
    if ($action === 'toggle_status') {
        $id     = intval($data['id']     ?? 0);
        $status = trim($data['status']   ?? '');
        if ($id === $me) {
            $response['message'] = 'Cannot modify your own account.';
            echo json_encode($response);
            exit();
        }
        if (!in_array($status, ['Active', 'Inactive'])) {
            $response['message'] = 'Invalid status.';
            echo json_encode($response);
            exit();
        }
        $pdo->prepare("UPDATE users SET status=:s WHERE id=:id")->execute([':s' => $status, ':id' => $id]);
        $pdo->prepare("INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at) VALUES (:uid, :un, 'Admin', 'Toggle Staff Status', :det, :ip, NOW())")
            ->execute([':uid' => $me, ':un' => $_SESSION['username'] ?? 'admin', ':det' => "Set user ID $id to $status", ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0']);
        $response['success'] = true;
        $response['message'] = 'Status updated.';
        echo json_encode($response);
        exit();
    }

    // ── DELETE ──
    if ($action === 'delete') {
        $id = intval($data['id'] ?? 0);
        if ($id === $me) {
            $response['message'] = 'Cannot delete your own account.';
            echo json_encode($response);
            exit();
        }
        $pdo->prepare("DELETE FROM users WHERE id=:id")->execute([':id' => $id]);
        $pdo->prepare("INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at) VALUES (:uid, :un, 'Admin', 'Delete Staff', :det, :ip, NOW())")
            ->execute([':uid' => $me, ':un' => $_SESSION['username'] ?? 'admin', ':det' => "Deleted user ID $id", ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0']);
        $response['success'] = true;
        $response['message'] = 'Staff deleted.';
        echo json_encode($response);
        exit();
    }

    $response['message'] = 'Invalid action.';
    echo json_encode($response);

} catch (PDOException $e) {
    error_log("manage_staff.php: " . $e->getMessage());
    $response['message'] = 'Database error.';
    echo json_encode($response);
}
?>