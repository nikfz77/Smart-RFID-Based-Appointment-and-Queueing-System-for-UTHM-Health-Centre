<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json');
$host='localhost';$dbname='queue_and_appointment_management';$username='root';$password='';
$response=['success'=>false,'student'=>null,'exists'=>false];
try{
    $matrix=strtoupper(trim($_GET['matrix_number']??''));
    if(empty($matrix)){$response['message']='Matrix number is required';echo json_encode($response);exit();}
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$username,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $stmt=$pdo->prepare("SELECT matrix_number,full_name,email,faculty,program,year_of_study FROM students WHERE matrix_number=:m LIMIT 1");
    $stmt->execute([':m'=>$matrix]);
    $student=$stmt->fetch(PDO::FETCH_ASSOC);
    if($student){
        $response['success']=true;$response['exists']=true;
        $response['message']='Patient found';$response['student']=$student;
    }else{
        $response['success']=false;$response['exists']=false;
        $response['message']='Patient not registered';
    }
}catch(PDOException $e){
    error_log("check_patient.php: ".$e->getMessage());
    $response['message']='Database error';
}
echo json_encode($response);
?>