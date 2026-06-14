<?php
// get_wait_estimate.php
// Returns estimated wait time for a given queue position and doctor
// Used by queue_display.html and queue_management.php

require_once __DIR__ . '/session_helper.php';
header('Content-Type: application/json');

$host='localhost'; $dbname='queue_and_appointment_management'; $db_user='root'; $db_pass='';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");

    // ── 1. Get average service time per doctor (last 30 days, minimum 3 samples) ──
    $avg_stmt = $pdo->query("
        SELECT
            assigned_doctor_id,
            COUNT(*) as sample_count,
            AVG(TIMESTAMPDIFF(MINUTE, checked_in_at, updated_at)) as avg_minutes
        FROM queue
        WHERE queue_status = 'Completed'
          AND assigned_doctor_id IS NOT NULL
          AND checked_in_at IS NOT NULL
          AND updated_at IS NOT NULL
          AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
          AND TIMESTAMPDIFF(MINUTE, checked_in_at, updated_at) BETWEEN 1 AND 60
        GROUP BY assigned_doctor_id
        HAVING sample_count >= 3
    ");
    $doctor_avgs = [];
    foreach ($avg_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $doctor_avgs[$row['assigned_doctor_id']] = round($row['avg_minutes'], 1);
    }

    // ── 2. Get today's queue with position info ──
    $queue_stmt = $pdo->query("
        SELECT
            q.id, q.queue_number, q.matrix_number, q.queue_status,
            q.assigned_doctor_id, q.called_at, q.created_at,
            s.full_name as student_name,
            u.full_name as doctor_name
        FROM queue q
        LEFT JOIN students s ON q.matrix_number = s.matrix_number
        LEFT JOIN users u ON q.assigned_doctor_id = u.id
        WHERE DATE(q.created_at) = CURDATE()
          AND q.queue_status IN ('Waiting', 'Being-Served')
        ORDER BY q.created_at ASC
    ");
    $queue_today = $queue_stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── 3. System-wide fallback average (if doctor has no history) ──
    $fallback_stmt = $pdo->query("
        SELECT AVG(TIMESTAMPDIFF(MINUTE, checked_in_at, updated_at)) as avg_minutes
        FROM queue
        WHERE queue_status = 'Completed'
          AND checked_in_at IS NOT NULL
          AND updated_at IS NOT NULL
          AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
          AND TIMESTAMPDIFF(MINUTE, checked_in_at, updated_at) BETWEEN 1 AND 60
    ");
    $fallback = $fallback_stmt->fetchColumn();
    $fallback_avg = $fallback ? round($fallback, 1) : 8; // default 8 min if no data

    // ── 4. Calculate wait estimate per patient ──
    $results = [];
    $position = 0;
    $cumulative_wait = 0;

    foreach ($queue_today as $patient) {
        if ($patient['queue_status'] === 'Being-Served') {
            // Currently being served — contributes remaining time to queue behind
            $doc_id = $patient['assigned_doctor_id'];
            $avg = $doctor_avgs[$doc_id] ?? $fallback_avg;
            // Estimate halfway through their consultation
            $cumulative_wait += round($avg / 2);
            $results[$patient['id']] = [
                'queue_number'  => $patient['queue_number'],
                'student_name'  => $patient['student_name'] ?? $patient['matrix_number'],
                'status'        => 'Being-Served',
                'position'      => 0,
                'wait_minutes'  => 0,
                'wait_label'    => 'Now serving',
                'avg_used'      => $avg,
            ];
        } else {
            // Waiting
            $position++;
            $doc_id = $patient['assigned_doctor_id'];
            $avg = $doc_id ? ($doctor_avgs[$doc_id] ?? $fallback_avg) : $fallback_avg;
            $cumulative_wait += $avg;
            $wait = round($cumulative_wait);

            if ($wait <= 5) {
                $label = 'About 5 min';
            } elseif ($wait <= 10) {
                $label = 'About 10 min';
            } elseif ($wait <= 20) {
                $label = "About {$wait} min";
            } elseif ($wait <= 60) {
                $rounded = round($wait / 5) * 5;
                $label = "~{$rounded} min";
            } else {
                $hrs = floor($wait / 60);
                $mins = $wait % 60;
                $label = $mins > 0 ? "~{$hrs}h {$mins}m" : "~{$hrs}h";
            }

            $results[$patient['id']] = [
                'queue_number'  => $patient['queue_number'],
                'student_name'  => $patient['student_name'] ?? $patient['matrix_number'],
                'status'        => 'Waiting',
                'position'      => $position,
                'wait_minutes'  => $wait,
                'wait_label'    => $label,
                'avg_used'      => $avg,
            ];
        }
    }

    // ── 5. Overall stats ──
    $total_waiting = count(array_filter($results, fn($r) => $r['status'] === 'Waiting'));

    echo json_encode([
        'success'        => true,
        'estimates'      => array_values($results),
        'total_waiting'  => $total_waiting,
        'fallback_avg'   => $fallback_avg,
        'doctor_avgs'    => $doctor_avgs,
    ]);

} catch (PDOException $e) {
    error_log("get_wait_estimate.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}
?>