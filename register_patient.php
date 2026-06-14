<?php
session_start();
header('Content-Type: application/json');
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
$response=['success'=>false,'message'=>''];
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    if(!isset($_SESSION['user_id'])){$response['message']='Unauthorized.';echo json_encode($response);exit();}
    if($_SERVER['REQUEST_METHOD']!=='POST'){$response['message']='Invalid request.';echo json_encode($response);exit();}

    $type     = trim($_POST['patient_type']??'student');
    $matrix   = strtoupper(trim($_POST['matrix_number']??''));
    $name     = trim($_POST['full_name']??'');
    $email    = trim($_POST['email']??'');
    $faculty  = trim($_POST['faculty']??'');
    $program  = trim($_POST['program']??'');
    $rfid_uid = strtoupper(trim($_POST['rfid_uid']??''));

    // Validate required
    $errors=[];
    if(empty($matrix)) $errors[]='Matrix/Staff number is required.';
    if(empty($name)||strlen($name)<3) $errors[]='Full name is required (min 3 characters).';
    if(empty($email)||!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Valid email is required.';
    if(empty($faculty)) $errors[]='Please select a faculty/department.';
    if(empty($program)) $errors[]='Program/position is required.';

    // Validate RFID if provided
    if(!empty($rfid_uid)&&!preg_match('/^[A-Fa-f0-9]{8,12}$/',$rfid_uid)) $errors[]='Invalid RFID UID format (8-12 hex characters).';

    if(!empty($errors)){$response['message']=implode(' ',$errors);echo json_encode($response);exit();}

    // Check duplicate matrix
    $chk=$pdo->prepare("SELECT id FROM students WHERE matrix_number=:m");
    $chk->execute([':m'=>$matrix]);
    if($chk->rowCount()>0){$response['message']='This matrix/staff number is already registered.';echo json_encode($response);exit();}

    // Check duplicate RFID if provided
    if(!empty($rfid_uid)){
        $chk2=$pdo->prepare("SELECT id FROM rfid_tags WHERE rfid_tag=:r");
        $chk2->execute([':r'=>$rfid_uid]);
        if($chk2->rowCount()>0){$response['message']='This RFID card is already assigned to another patient.';echo json_encode($response);exit();}
    }

    // Insert student/staff
    $pdo->prepare("INSERT INTO students (matrix_number,full_name,email,faculty,program,year_of_study,created_at) VALUES (:m,:n,:e,:f,:p,:y,NOW())")
        ->execute([':m'=>$matrix,':n'=>$name,':e'=>$email,':f'=>$faculty,':p'=>$program,':y'=>0]);

    // Insert RFID if provided
    if(!empty($rfid_uid)){
        $pdo->prepare("INSERT INTO rfid_tags (rfid_tag,matrix_number,status,created_at) VALUES (:r,:m,'Active',NOW())")
            ->execute([':r'=>$rfid_uid,':m'=>$matrix]);
    }

    // Log
    $pdo->prepare("INSERT INTO system_logs (user_id,username,role,action,details,ip_address,created_at) VALUES (:uid,:un,:role,'Register Patient',:det,:ip,NOW())")
        ->execute([
            ':uid'=>$_SESSION['user_id'],
            ':un'=>$_SESSION['username']??'staff',
            ':role'=>$_SESSION['role']??'Staff',
            ':det'=>"Registered: $matrix ($name) Type: $type".(!empty($rfid_uid)?" RFID: $rfid_uid":''),
            ':ip'=>$_SERVER['REMOTE_ADDR']??'0.0.0.0'
        ]);

    $response['success']=true;
    $response['message']="$name ($matrix) registered successfully!";

}catch(PDOException $e){
    error_log("register_patient.php: ".$e->getMessage());
    $response['message']='Database error. Please try again.';
}
echo json_encode($response);
?>