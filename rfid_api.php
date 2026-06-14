<?php
header('Content-Type: text/plain');
date_default_timezone_set('Asia/Kuala_Lumpur');

$host    = 'localhost';
$dbname  = 'queue_and_appointment_management';
$db_user = 'root';
$db_pass = '';

$rfid_uid = strtoupper(trim($_GET['uid'] ?? ''));
$node_id  = intval($_GET['node_id'] ?? 1);

// Node ID to Faculty mapping
$node_map = [
    1 => 'FSKTM',
    2 => 'FKEE',
    3 => 'FKMP',
    4 => 'FKAAB',
    5 => 'FPTP',
    6 => 'FPTV',
];
$source_faculty = $node_map[$node_id] ?? 'UNKNOWN';

if (empty($rfid_uid)) {
    echo "FAILED:No UID received";
    exit();
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");

    // Add columns if not exist
    try {
        $pdo->exec("ALTER TABLE queue ADD COLUMN IF NOT EXISTS node_id INT DEFAULT 1");
        $pdo->exec("ALTER TABLE queue ADD COLUMN IF NOT EXISTS source_faculty VARCHAR(20) DEFAULT NULL");
    } catch(Exception $e){}

    // 1. Check if RFID is registered
    $stmt = $pdo->prepare("
        SELECT r.matrix_number, r.status as card_status,
               s.full_name, s.email
        FROM rfid_tags r
        JOIN students s ON r.matrix_number = s.matrix_number
        WHERE r.rfid_tag = :rfid_tag
        LIMIT 1
    ");
    $stmt->execute([':rfid_tag' => $rfid_uid]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$card) {
        echo "FAILED:Not Registered";
        exit();
    }

    if ($card['card_status'] !== 'Active') {
        echo "FAILED:Card Inactive";
        exit();
    }

    $matrix_number = $card['matrix_number'];
    $student_name  = $card['full_name'];

    // 2. Check if already in queue today
    $check = $pdo->prepare("
        SELECT id, queue_number FROM queue
        WHERE matrix_number = :matrix_number
          AND DATE(created_at) = CURDATE()
          AND queue_status IN ('Waiting', 'Being-Served')
        LIMIT 1
    ");
    $check->execute([':matrix_number' => $matrix_number]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        echo "EXISTS:{$existing['queue_number']}:{$student_name}:{$source_faculty}";
        exit();
    }

    // 3. Generate queue number
    $max_stmt = $pdo->query("
        SELECT MAX(CAST(SUBSTRING(queue_number, 2) AS UNSIGNED)) as max_num
        FROM queue
        WHERE queue_number LIKE 'Q%'
          AND DATE(created_at) = CURDATE()
    ");
    $max_num      = $max_stmt->fetchColumn();
    $next_number  = (int)$max_num + 1;
    $queue_number = 'Q' . str_pad($next_number, 3, '0', STR_PAD_LEFT);

    // 4. Insert into queue with node info
    $insert = $pdo->prepare("
        INSERT INTO queue
            (queue_number, matrix_number, service_type, queue_status, is_priority,
             checked_in_at, node_id, source_faculty, created_at)
        VALUES
            (:queue_number, :matrix_number, 'General Consultation', 'Waiting', 0,
             NOW(), :node_id, :source_faculty, NOW())
    ");
    $insert->execute([
        ':queue_number'   => $queue_number,
        ':matrix_number'  => $matrix_number,
        ':node_id'        => $node_id,
        ':source_faculty' => $source_faculty,
    ]);

    // 5. Log activity
    $pdo->prepare("
        INSERT INTO system_logs (user_id, username, role, action, details, ip_address, created_at)
        VALUES (0, :matrix, 'Student', 'RFID Check-in', :details, :ip, NOW())
    ")->execute([
        ':matrix'  => $matrix_number,
        ':details' => "RFID: $rfid_uid → $matrix_number ($student_name) → $queue_number [$source_faculty Node $node_id]",
        ':ip'      => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
    ]);

    // 6. Return to device — include faculty
    echo "SUCCESS:{$queue_number}:{$student_name}:{$source_faculty}";

} catch (PDOException $e) {
    error_log("DB Error in rfid_api.php: " . $e->getMessage());
    echo "FAILED:Server Error";
}
?>