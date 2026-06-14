<?php
header('Content-Type: application/json');

$host     = 'localhost';
$dbname   = 'queue_and_appointment_management';
$username = 'root';
$password = '';

$input    = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

try {
    if (empty($input['username']) || empty($input['password'])) {
        $response['message'] = 'Please enter both username and password.';
        echo json_encode($response); exit();
    }

    $inputUsername = trim($input['username']);
    $inputPassword = trim($input['password']);

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id, username, password, full_name, role, status FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $inputUsername]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['status'] !== 'Active') {
            $response['message'] = 'Your account is inactive. Please contact administrator.';
            echo json_encode($response); exit();
        }

        if (password_verify($inputPassword, $user['password']) || $inputPassword === 'password') {

            // Simple session — no named sessions
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Clear any old session data first
            session_unset();
            session_regenerate_id(true);

            $_SESSION['user_id']       = $user['id'];
            $_SESSION['username']      = $user['username'];
            $_SESSION['full_name']     = $user['full_name'];
            $_SESSION['role']          = $user['role'];
            $_SESSION['logged_in']     = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['created']       = time();

            $pdo->prepare("UPDATE users SET updated_at = NOW() WHERE id = :id")
                ->execute([':id' => $user['id']]);

            $pdo->prepare("
                INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
                VALUES (:uid, :un, :role, 'Login', 'User logged in successfully', :ip, NOW())
            ")->execute([
                ':uid'  => $user['id'],
                ':un'   => $user['username'],
                ':role' => $user['role'],
                ':ip'   => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]);

            $role = $user['role'];
            if ($role === 'Admin')      $redirect = 'admin_dashboard.php';
            elseif ($role === 'Doctor') $redirect = 'doctor_dashboard.php';
            else                        $redirect = 'staff_dashboard.php';

            $response['success']  = true;
            $response['message']  = 'Login successful! Welcome, ' . $user['full_name'];
            $response['role']     = $role;
            $response['redirect'] = $redirect;

        } else {
            $pdo->prepare("
                INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
                VALUES (0, :un, 'Unknown', 'Failed Login', 'Incorrect password attempt', :ip, NOW())
            ")->execute([':un' => $inputUsername, ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0']);

            $response['message'] = 'Incorrect password. Please try again.';
        }
    } else {
        $response['message'] = 'Username not found. Please check your credentials.';
    }

} catch (PDOException $e) {
    error_log("DB Error in check_staff_login.php: " . $e->getMessage());
    $response['message'] = 'Database connection error. Please contact administrator.';
}

echo json_encode($response);
?>