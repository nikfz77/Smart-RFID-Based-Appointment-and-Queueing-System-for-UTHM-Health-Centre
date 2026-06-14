<?php
// Called by queue_display every 4 seconds to auto-skip patients who didn't arrive in 5 min
header('Content-Type: application/json');
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");

    // Find patients called more than 5 minutes ago who haven't arrived
    $stmt=$pdo->prepare("
        UPDATE queue 
        SET queue_status='Waiting', called_at=NULL
        WHERE called_at IS NOT NULL
          AND called_at < DATE_SUB(NOW(), INTERVAL 5 MINUTE)
          AND queue_status='Waiting'
    ");
    $stmt->execute();
    $affected=$stmt->rowCount();
    echo json_encode(['success'=>true,'skipped'=>$affected]);
}catch(PDOException $e){
    echo json_encode(['success'=>false]);
}