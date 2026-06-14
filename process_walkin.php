<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
$response = ['success' => false, 'message' => ''];

function isOpen() {
    // All time/day restrictions disabled for testing
    return ['open' => true, 'msg' => ''];
}

$hours = isOpen();
if (!$hours['open']) {
    echo json_encode(['success' => false, 'message' => $hours['msg']]);
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");

    $matrix         = strtoupper(trim($_POST['matrix_number'] ?? ''));
    $service        = trim($_POST['service'] ?? 'General Consultation');
    $is_priority    = isset($_POST['is_priority']) ? 1 : 0;
    $scheduled_time = trim($_POST['scheduled_time'] ?? '');

    if (empty($matrix)) {
        echo json_encode(['success' => false, 'message' => 'Matrix number is required.']);
        exit();
    }

    $stmt = $pdo->prepare("SELECT matrix_number, full_name, email, faculty, program FROM students WHERE matrix_number=:m LIMIT 1");
    $stmt->execute([':m' => $matrix]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'Patient not registered. Please register first.']);
        exit();
    }

    $chk = $pdo->prepare("SELECT id, queue_number FROM queue WHERE matrix_number=:m AND DATE(created_at)=CURDATE() AND queue_status IN ('Waiting','Being-Served') LIMIT 1");
    $chk->execute([':m' => $matrix]);
    $existing = $chk->fetch(PDO::FETCH_ASSOC);
    if ($existing) {
        echo json_encode(['success' => false, 'message' => "Patient already in queue today ({$existing['queue_number']})."]);
        exit();
    }

    $max          = $pdo->query("SELECT MAX(CAST(SUBSTRING(queue_number,2) AS UNSIGNED)) FROM queue WHERE queue_number LIKE 'Q%' AND DATE(created_at)=CURDATE()")->fetchColumn();
    $queue_number = 'Q' . str_pad((int)$max + 1, 3, '0', STR_PAD_LEFT);

    $scheduled_datetime     = null;
    $scheduled_time_display = null;
    if (!empty($scheduled_time) && preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $scheduled_time)) {
        $scheduled_datetime     = date('Y-m-d') . ' ' . $scheduled_time . ':00';
        $scheduled_time_display = date('h:i A', strtotime($scheduled_time));
    }

    $ins = $pdo->prepare("INSERT INTO queue (queue_number, matrix_number, service_type, queue_status, is_priority, checked_in_at, scheduled_time, created_at) VALUES (:qn, :mx, :svc, 'Waiting', :pri, NOW(), :st, NOW())");
    $ins->execute([':qn' => $queue_number, ':mx' => $matrix, ':svc' => $service, ':pri' => $is_priority, ':st' => $scheduled_datetime]);

    $pdo->prepare("INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at) VALUES (:uid, :un, :role, 'Walk-In Queue', :det, :ip, NOW())")
        ->execute([':uid' => $_SESSION['user_id'], ':un' => $_SESSION['username'], ':role' => $_SESSION['role'], ':det' => "Walk-in: $matrix → $queue_number ($service)" . ($scheduled_datetime ? " @ $scheduled_time" : ''), ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0']);

    $email_sent = false;
    if ($scheduled_datetime && !empty($student['email'])) {
        $email_sent = sendQueueEmail($student['email'], $student['full_name'], $queue_number, $service, $scheduled_time, $matrix);
    }

    $response['success']        = true;
    $response['queue_number']   = $queue_number;
    $response['student_name']   = $student['full_name'];
    $response['matrix_number']  = $matrix;
    $response['service']        = $service;
    $response['scheduled_time'] = $scheduled_time_display;
    $response['email_sent']     = $email_sent;
    $response['message']        = "Queue $queue_number created!" . ($email_sent ? ' Email sent to patient.' : '');

} catch (PDOException $e) {
    error_log("process_walkin.php: " . $e->getMessage());
    $response['message'] = 'Database error.';
}

echo json_encode($response);

function sendQueueEmail($to_email, $patient_name, $queue_number, $service, $time, $matrix_number) {
    $time_12hr = date('h:i A', strtotime($time));
    $date_str  = date('l, d F Y');
    $subject   = "Your Queue Appointment - PKU UTHM ($queue_number)";
    $body      = "
    <div style='font-family:Arial,sans-serif;max-width:560px;margin:0 auto'>
        <div style='background:#14532d;padding:30px;text-align:center'>
            <h1 style='color:white;margin:0'>PKU UTHM</h1>
            <p style='color:rgba(255,255,255,.8);margin:5px 0 0;font-size:13px'>Pusat Kesihatan Universiti</p>
        </div>
        <div style='padding:30px;background:white'>
            <p>Dear <strong>$patient_name</strong>,</p>
            <p style='color:#6b7280'>Your queue number is <strong>$queue_number</strong> on $date_str at $time_12hr.</p>
            <p style='color:#6b7280'>Service: $service | Matrix: $matrix_number</p>
        </div>
    </div>";

    if (!file_exists(__DIR__ . '/vendor/autoload.php')) return false;
    require_once __DIR__ . '/vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'uthmhealthcentre@gmail.com';
        $mail->Password   = 'tjpx qyev tyvi ydnt';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->setFrom('uthmhealthcentre@gmail.com', 'PKU UTHM Queue System');
        $mail->addAddress($to_email, $patient_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error in process_walkin: " . $e->getMessage());
        return false;
    }
}
?>