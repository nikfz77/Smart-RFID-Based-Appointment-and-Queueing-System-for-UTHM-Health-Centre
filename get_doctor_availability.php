<?php
// get_doctor_availability.php
// Returns available doctors with smart recommendation scoring
require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    echo json_encode([]);
    exit();
}

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Auto clock-out inactive doctors
    $pdo->exec("
        UPDATE users
        SET is_available = 0
        WHERE role = 'Doctor'
          AND is_available = 1
          AND last_active IS NOT NULL
          AND last_active < DATE_SUB(NOW(), INTERVAL 30 MINUTE)
    ");

    // Get doctors with patient counts and average service time
    $stmt = $pdo->query("
        SELECT
            u.id, u.full_name, u.room, u.is_available, u.clocked_in_at, u.last_active,

            -- Today's active patients
            (SELECT COUNT(*) FROM queue q
             WHERE q.assigned_doctor_id = u.id
               AND q.queue_status IN ('Waiting','Being-Served')
               AND DATE(q.created_at) = CURDATE()) as patient_count,

            -- Today's completed
            (SELECT COUNT(*) FROM queue q2
             WHERE q2.assigned_doctor_id = u.id
               AND q2.queue_status = 'Completed'
               AND DATE(q2.created_at) = CURDATE()) as completed_today,

            -- Average service time in minutes (last 30 days)
            (SELECT ROUND(AVG(TIMESTAMPDIFF(MINUTE, q3.checked_in_at, q3.updated_at)), 1)
             FROM queue q3
             WHERE q3.assigned_doctor_id = u.id
               AND q3.queue_status = 'Completed'
               AND q3.checked_in_at IS NOT NULL
               AND q3.updated_at IS NOT NULL
               AND DATE(q3.created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
               AND TIMESTAMPDIFF(MINUTE, q3.checked_in_at, q3.updated_at) BETWEEN 1 AND 60
            ) as avg_service_min

        FROM users u
        WHERE u.role = 'Doctor'
        ORDER BY u.is_available DESC, u.room ASC
    ");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Smart recommendation scoring ──
    // Score = lower is better
    // Formula: (patient_count × 10) + avg_service_min
    // Ties broken by completed_today (more experience = better)

    $available = array_filter($doctors, fn($d) => $d['is_available'] == 1 && $d['room']);
    $available = array_values($available);

    $best_score = PHP_INT_MAX;
    $best_id    = null;

    foreach ($available as &$doc) {
        $avg = $doc['avg_service_min'] ?? 8; // default 8 min
        $score = ($doc['patient_count'] * 10) + $avg;
        $doc['recommendation_score'] = $score;
        $doc['avg_service_min']      = $avg;
        $doc['is_recommended']       = false;

        // Estimated wait for next patient assigned to this doctor
        $doc['est_wait_min'] = round($doc['patient_count'] * $avg);

        if ($score < $best_score) {
            $best_score = $score;
            $best_id    = $doc['id'];
        }
    }
    unset($doc);

    // Mark recommended doctor
    foreach ($available as &$doc) {
        if ($doc['id'] === $best_id) {
            $doc['is_recommended'] = true;
        }
    }
    unset($doc);

    // Merge back with unavailable doctors (no scoring needed)
    $unavailable = array_filter($doctors, fn($d) => !($d['is_available'] == 1 && $d['room']));
    foreach ($unavailable as &$doc) {
        $doc['is_recommended']       = false;
        $doc['recommendation_score'] = null;
        $doc['est_wait_min']         = null;
        $doc['avg_service_min']      = $doc['avg_service_min'] ?? null;
    }
    unset($doc);

    $output = array_merge($available, array_values($unavailable));

    echo json_encode($output);

} catch (PDOException $e) {
    echo json_encode([]);
}
?>