<?php
session_start();
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kuala_Lumpur');

$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Gmail config
define('GMAIL_USER', 'uthmhealthcentre@gmail.com');
define('GMAIL_PASS', 'tjpx qyev tyvi ydnt');
define('SITE_URL',   'http://localhost/smart_pku_system');

$response=['success'=>false,'message'=>''];

try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // Set MySQL timezone to match PHP
    $pdo->exec("SET time_zone = '+08:00'");

    $data   = json_decode(file_get_contents('php://input'),true);
    $action = trim($data['action']??'');

    // ── SEND RESET EMAIL ─────────────────────────────
    if($action==='send_reset'){
        $email = strtolower(trim($data['email']??''));
        if(empty($email)||!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $response['message']='Please enter a valid email address.';
            echo json_encode($response);exit();
        }

        // Find user by email
        $stmt=$pdo->prepare("SELECT id,username,full_name,email FROM users WHERE email=:e AND status='Active' LIMIT 1");
        $stmt->execute([':e'=>$email]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            $response['success']=true;
            $response['message']='If this email exists, a reset link has been sent.';
            echo json_encode($response);exit();
        }

        // Generate token
        $token      = bin2hex(random_bytes(32));
        $token_hash = hash('sha256',$token);
        $expires    = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token to DB
        $pdo->prepare("UPDATE users SET reset_token=:t, reset_token_expires=:e WHERE id=:id")
            ->execute([':t'=>$token_hash,':e'=>$expires,':id'=>$user['id']]);

        // Build reset link
        $reset_link = SITE_URL.'/reset_password_form.php?token='.$token;

        // Send email via PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = GMAIL_USER;
        $mail->Password   = GMAIL_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom(GMAIL_USER, 'PKU UTHM Queue System');
        $mail->addAddress($user['email'], $user['full_name']);
        $mail->Subject = 'Password Reset — PKU UTHM Queue System';
        $mail->isHTML(true);
        $mail->Body = "
        <div style='font-family:DM Sans,sans-serif;max-width:500px;margin:0 auto;padding:32px;background:#f7f9fb;border-radius:14px'>
            <div style='background:#14532d;padding:20px 24px;border-radius:10px;margin-bottom:24px'>
                <h2 style='color:white;margin:0;font-size:18px'>PKU UTHM Queue System</h2>
                <p style='color:rgba(255,255,255,.7);margin:4px 0 0;font-size:13px'>Password Reset Request</p>
            </div>
            <p style='font-size:15px;color:#0f1923'>Hi <strong>{$user['full_name']}</strong>,</p>
            <p style='font-size:14px;color:#6b7c8d;margin:12px 0'>We received a request to reset your password. Click the button below to set a new password:</p>
            <div style='text-align:center;margin:28px 0'>
                <a href='{$reset_link}' style='background:#1a56db;color:white;padding:13px 32px;border-radius:10px;text-decoration:none;font-weight:700;font-size:15px;display:inline-block'>Reset My Password</a>
            </div>
            <p style='font-size:12px;color:#6b7c8d'>This link expires in <strong>1 hour</strong>. If you did not request this, ignore this email.</p>
            <hr style='border:none;border-top:1px solid #e4e9ee;margin:20px 0'>
            <p style='font-size:12px;color:#b0b7c3;text-align:center'>PKU UTHM · Pusat Kesihatan Universiti</p>
        </div>";

        $mail->send();

        // Log
        $pdo->prepare("INSERT INTO system_logs (user_id,username,role,action,details,ip_address,created_at) VALUES (:uid,:un,:role,'Password Reset Request','Reset email sent to {$user['email']}',:ip,NOW())")
            ->execute([':uid'=>$user['id'],':un'=>$user['username'],':role'=>'Staff',':ip'=>$_SERVER['REMOTE_ADDR']??'0.0.0.0']);

        $response['success']=true;
        $response['message']='Reset link sent! Check your email inbox.';
        echo json_encode($response);exit();
    }

    // ── VERIFY TOKEN ─────────────────────────────────
    if($action==='verify_token'){
        $token      = trim($data['token']??'');
        $token_hash = hash('sha256',$token);

        $stmt=$pdo->prepare("SELECT id,username,full_name FROM users WHERE reset_token=:t AND reset_token_expires > NOW() LIMIT 1");
        $stmt->execute([':t'=>$token_hash]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            $response['message']='Reset link is invalid or has expired. Please request a new one.';
            echo json_encode($response);exit();
        }

        $response['success']   = true;
        $response['full_name'] = $user['full_name'];
        echo json_encode($response);exit();
    }

    // ── RESET PASSWORD ───────────────────────────────
    if($action==='reset'){
        $token        = trim($data['token']??'');
        $new_password = trim($data['new_password']??'');
        $token_hash   = hash('sha256',$token);

        if(empty($new_password)||strlen($new_password)<6){
            $response['message']='Password must be at least 6 characters.';
            echo json_encode($response);exit();
        }

        $stmt=$pdo->prepare("SELECT id,username FROM users WHERE reset_token=:t AND reset_token_expires > NOW() LIMIT 1");
        $stmt->execute([':t'=>$token_hash]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            $response['message']='Reset link is invalid or has expired.';
            echo json_encode($response);exit();
        }

        // Update password + clear token
        $hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE users SET password=:p, reset_token=NULL, reset_token_expires=NULL WHERE id=:id")
            ->execute([':p'=>$hashed,':id'=>$user['id']]);

        // Log
        $pdo->prepare("INSERT INTO system_logs (user_id,username,role,action,details,ip_address,created_at) VALUES (:uid,:un,'Staff','Password Reset','Password reset successfully',:ip,NOW())")
            ->execute([':uid'=>$user['id'],':un'=>$user['username'],':ip'=>$_SERVER['REMOTE_ADDR']??'0.0.0.0']);

        $response['success']=true;
        $response['message']='Password reset successfully!';
        echo json_encode($response);exit();
    }

    $response['message']='Invalid action.';
    echo json_encode($response);

}catch(Exception $e){
    error_log("reset_password.php: ".$e->getMessage());
    $response['message']='Error: '.$e->getMessage();
    echo json_encode($response);
}
?>