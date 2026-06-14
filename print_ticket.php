<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: staff_login.php');
    exit();
}

$queue_number  = htmlspecialchars($_GET['queue_number']  ?? '');
$student_name  = htmlspecialchars($_GET['student_name']  ?? 'Walk-in Patient');
$matrix_number = htmlspecialchars($_GET['matrix_number'] ?? '');
$service       = htmlspecialchars($_GET['service']       ?? '');
$time          = date('h:i A');
$date          = date('d F Y');

$assigned_room  = '';
$doctor_name    = '';
$scheduled_time = '';
try {
    $pdo  = new PDO("mysql:host=localhost;dbname=queue_and_appointment_management;charset=utf8mb4", 'root', '');
    $stmt = $pdo->prepare("
        SELECT q.assigned_room, q.scheduled_time, u.full_name as doctor_name
        FROM queue q
        LEFT JOIN users u ON q.assigned_doctor_id = u.id
        WHERE q.queue_number = :qn AND DATE(q.created_at) = CURDATE()
        ORDER BY q.id DESC LIMIT 1
    ");
    $stmt->execute([':qn' => $_GET['queue_number'] ?? '']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $assigned_room  = htmlspecialchars($row['assigned_room'] ?? '');
        $doctor_name    = htmlspecialchars($row['doctor_name']   ?? '');
        $scheduled_time = $row['scheduled_time'] ? date('h:i A', strtotime($row['scheduled_time'])) : '';
    }
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Queue Ticket - <?php echo $queue_number; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .ticket { background: white; width: 320px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.15); }
        .ticket-header { background: linear-gradient(135deg, #0d47a1, #1976d2); color: white; padding: 25px 20px; text-align: center; }
        .ticket-header h1 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
        .ticket-header p  { font-size: 12px; opacity: 0.85; }
        .ticket-body { padding: 25px 20px; }
        .queue-number-display { text-align: center; margin: 10px 0 25px; padding: 20px; background: #e3f2fd; border-radius: 12px; border: 3px dashed #0d47a1; }
        .queue-label { font-size: 13px; color: #6b7280; font-weight: 600; margin-bottom: 8px; }
        .queue-num   { font-family: 'Orbitron', sans-serif; font-size: 60px; font-weight: 900; color: #ff1744; line-height: 1; }
        .ticket-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px dashed #e0e0e0; font-size: 13px; }
        .ticket-row:last-of-type { border-bottom: none; }
        .ticket-row .label { color: #6b7280; font-weight: 600; }
        .ticket-row .value { color: #263238; font-weight: 700; text-align: right; max-width: 60%; }
        .ticket-footer { background: #f5f7fa; padding: 15px 20px; text-align: center; border-top: 2px dashed #e0e0e0; }
        .ticket-footer p { font-size: 11px; color: #6b7280; line-height: 1.6; }
        .btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .btn-print { padding: 12px 25px; background: linear-gradient(135deg, #0d47a1, #1976d2); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 14px; }
        .btn-close { padding: 12px 25px; background: #f0f0f0; color: #263238; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 14px; }
        @media print { body { background: white; padding: 0; } .ticket { box-shadow: none; border: 1px solid #ddd; } .btn-group { display: none; } }
    </style>
</head>
<body>
<div>
    <div class="ticket">
        <div class="ticket-header">
            <h1>PKU UTHM</h1>
            <p>Pusat Kesihatan Universiti</p>
        </div>
        <div class="ticket-body">
            <div class="queue-number-display">
                <div class="queue-label">YOUR QUEUE NUMBER</div>
                <div class="queue-num"><?php echo $queue_number; ?></div>
            </div>
            <div class="ticket-row"><span class="label">Name</span><span class="value"><?php echo $student_name; ?></span></div>
            <div class="ticket-row"><span class="label">Matrix No.</span><span class="value"><?php echo $matrix_number ?: '-'; ?></span></div>
            <div class="ticket-row"><span class="label">Service</span><span class="value"><?php echo $service ?: '-'; ?></span></div>
            <?php if ($assigned_room): ?>
            <div class="ticket-row"><span class="label">Room</span><span class="value" style="color:#1a56db;font-weight:800"><?php echo $assigned_room; ?></span></div>
            <?php endif; ?>
            <?php if ($doctor_name): ?>
            <div class="ticket-row"><span class="label">Doctor</span><span class="value" style="color:#059669"><?php echo $doctor_name; ?></span></div>
            <?php endif; ?>
            <div class="ticket-row"><span class="label">Date</span><span class="value"><?php echo $date; ?></span></div>
            <div class="ticket-row"><span class="label">Scheduled</span><span class="value"><?php echo $scheduled_time ?: $time; ?></span></div>
        </div>
        <div class="ticket-footer">
            <p>Please wait in the waiting area.<br>Your number will be called shortly.<br><strong>Operating Hours: Mon–Fri 8:00AM–5:00PM</strong></p>
        </div>
    </div>
    <div class="btn-group">
        <button class="btn-print" onclick="window.print()">Print Ticket</button>
        <button class="btn-close" onclick="window.close()">Close</button>
    </div>
</div>
<script>
    window.onload = function() { setTimeout(() => window.print(), 500); };
</script>
</body>
</html>