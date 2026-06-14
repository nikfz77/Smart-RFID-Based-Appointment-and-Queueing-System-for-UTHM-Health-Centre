<?php
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kuala_Lumpur');

$host    = 'localhost';
$dbname  = 'queue_and_appointment_management';
$db_user = 'root';
$db_pass = '';

$input    = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone = '+08:00'");

    $queue_id       = intval($input['queue_id']      ?? 0);
    $service_type   = trim($input['service_type']    ?? '');
    $scheduled_time = trim($input['scheduled_time']  ?? '');

    if (!$queue_id || !$scheduled_time) {
        $response['message'] = 'Queue ID and time are required.';
        echo json_encode($response);
        exit();
    }

    if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $scheduled_time)) {
        $response['message'] = 'Invalid time format. Use HH:MM (e.g. 14:00)';
        echo json_encode($response);
        exit();
    }

    // Get queue details + student info + assigned doctor/room
    $stmt = $pdo->prepare("
        SELECT q.id, q.queue_number, q.matrix_number, q.service_type, q.queue_status,
               q.assigned_room, q.assigned_doctor_id,
               s.full_name as student_name, s.email, s.phone,
               u.full_name as doctor_name, u.room as doctor_room
        FROM queue q
        LEFT JOIN students s ON q.matrix_number = s.matrix_number
        LEFT JOIN users u ON q.assigned_doctor_id = u.id
        WHERE q.id = :queue_id
        LIMIT 1
    ");
    $stmt->execute([':queue_id' => $queue_id]);
    $queue = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$queue) {
        $response['message'] = 'Queue entry not found.';
        echo json_encode($response);
        exit();
    }

    if ($queue['queue_status'] === 'Completed' || $queue['queue_status'] === 'Cancelled') {
        $response['message'] = 'Cannot set time for a completed or cancelled queue.';
        echo json_encode($response);
        exit();
    }

    $scheduled_datetime = date('Y-m-d') . ' ' . $scheduled_time . ':00';
    $update_service = !empty($service_type) ? $service_type : $queue['service_type'];
    $room        = $queue['assigned_room'] ?? $queue['doctor_room'] ?? null;
    $doctor_name = $queue['doctor_name'] ?? null;

    // Update queue
    $pdo->prepare("
        UPDATE queue
        SET service_type = :service_type,
            scheduled_time = :scheduled_time,
            updated_at = NOW()
        WHERE id = :id
    ")->execute([
        ':service_type'   => $update_service,
        ':scheduled_time' => $scheduled_datetime,
        ':id'             => $queue_id
    ]);

    // Log activity
    $pdo->prepare("
        INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
        VALUES (:uid, :uname, :role, 'Set Queue Time', :details, :ip, NOW())
    ")->execute([
        ':uid'     => $_SESSION['user_id'],
        ':uname'   => $_SESSION['username'],
        ':role'    => $_SESSION['role'],
        ':details' => "Queue {$queue['queue_number']} set to $scheduled_time for {$queue['matrix_number']}" . ($room ? " in $room" : ''),
        ':ip'      => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
    ]);

    // Send email to patient
    $email_sent = false;
    if (!empty($queue['email'])) {
        $email_result = sendQueueEmail(
            $queue['email'],
            $queue['student_name']  ?? $queue['matrix_number'],
            $queue['queue_number'],
            $update_service,
            $scheduled_time,
            $queue['matrix_number'],
            $room,
            $doctor_name
        );
        $email_sent = $email_result['success'];
    }

    $response['success']    = true;
    $response['message']    = "Time set to $scheduled_time for {$queue['queue_number']}." . ($email_sent ? ' Email sent to patient.' : ' (No email found for patient)');
    $response['email_sent'] = $email_sent;

} catch (PDOException $e) {
    error_log("DB Error in set_queue_time.php: " . $e->getMessage());
    $response['message'] = 'Database error occurred.';
} catch (Exception $e) {
    error_log("Error in set_queue_time.php: " . $e->getMessage());
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

// ── SEND EMAIL FUNCTION ──────────────────────────────────
function sendQueueEmail($to_email, $patient_name, $queue_number, $service, $time, $matrix_number, $room = null, $doctor_name = null) {
    $result    = ['success' => false, 'message' => ''];
    $time_12hr = date('h:i A', strtotime($time));
    $date_str  = date('l, d F Y');
    $subject   = "Your Queue Appointment - PKU UTHM ($queue_number)";

    $room_row   = $room        ? "<tr><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;font-weight:700;color:#263238'>Room</td><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;color:#1a56db;font-weight:700;font-size:16px'>$room</td></tr>" : '';
    $doctor_row = $doctor_name ? "<tr><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;font-weight:700;color:#263238'>Doctor</td><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;color:#16a34a;font-weight:600'>$doctor_name</td></tr>" : '';

    $body = "
    <div style='font-family:Arial,sans-serif;max-width:560px;margin:0 auto;background:#f5f7fa;padding:20px'>
        <div style='background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1)'>
            <div style='background:#14532d;padding:30px;text-align:center'>
                <h1 style='color:white;margin:0;font-size:22px'>PKU UTHM</h1>
                <p style='color:rgba(255,255,255,.8);margin:5px 0 0;font-size:13px'>Pusat Kesihatan Universiti</p>
            </div>
            <div style='padding:30px'>
                <p style='font-size:16px;color:#263238'>Dear <strong>$patient_name</strong>,</p>
                <p style='color:#6b7280;font-size:14px'>Your queue appointment has been scheduled. Please see the details below:</p>

                <div style='background:#dcfce7;border:3px dashed #16a34a;border-radius:14px;padding:25px;text-align:center;margin:20px 0'>
                    <div style='font-size:13px;color:#6b7280;font-weight:600;margin-bottom:8px'>YOUR QUEUE NUMBER</div>
                    <div style='font-size:52px;font-weight:900;color:#16a34a;line-height:1'>$queue_number</div>
                </div>

                <table style='width:100%;border-collapse:collapse;margin:20px 0'>
                    <tr><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;font-weight:700;color:#263238;width:40%'>Matrix No.</td><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;color:#16a34a;font-weight:600'>$matrix_number</td></tr>
                    <tr><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;font-weight:700;color:#263238'>Service</td><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;color:#16a34a;font-weight:600'>$service</td></tr>
                    <tr><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;font-weight:700;color:#263238'>Date</td><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;color:#16a34a;font-weight:600'>$date_str</td></tr>
                    <tr><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;font-weight:700;color:#263238'>Time</td><td style='padding:12px 16px;border-bottom:1px solid #f0f0f0;color:#dc2626;font-weight:700;font-size:18px'>$time_12hr</td></tr>
                    $doctor_row
                    $room_row
                </table>

                <div style='background:#fef9c3;border-left:4px solid #ca8a04;padding:14px 18px;border-radius:8px;font-size:13px;color:#92400e;margin-top:20px'>
                    Please arrive at least <strong>10 minutes early</strong>. Bring your student card and any relevant medical documents.
                </div>
            </div>
            <div style='background:#f5f7fa;padding:20px;text-align:center;font-size:12px;color:#6b7280;border-top:1px solid #e0e0e0'>
                <p style='margin:0'>PKU UTHM · Universiti Tun Hussein Onn Malaysia</p>
                <p style='margin:5px 0 0;color:#bbb'>This is an automated email. Please do not reply.</p>
            </div>
        </div>
    </div>";

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
        $result['success'] = true;
        $result['message'] = 'Email sent successfully';
    } catch (Exception $e) {
        error_log("PHPMailer Error in set_queue_time: " . $e->getMessage());
        $result['message'] = $e->getMessage();
    }

    return $result;
}
?>