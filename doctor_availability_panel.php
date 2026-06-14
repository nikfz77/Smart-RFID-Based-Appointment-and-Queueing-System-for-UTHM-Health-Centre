<?php
// Embeddable panel — included in staff pages to show doctor availability
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $doctors=$pdo->query("
        SELECT u.id,u.full_name,u.room,u.is_available,u.clocked_in_at,
        (SELECT COUNT(*) FROM queue q WHERE q.assigned_doctor_id=u.id
         AND q.queue_status IN ('Waiting','Being-Served') AND DATE(q.created_at)=CURDATE()) as patient_count
        FROM users u WHERE u.role='Doctor' ORDER BY u.is_available DESC,u.room ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
}catch(Exception $e){$doctors=[];}
?>
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;margin-bottom:18px">
    <div style="padding:12px 18px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;background:#f8fafc">
        <h2 style="font-size:13px;font-weight:700;color:#0f172a;display:flex;align-items:center;gap:7px">🩺 Doctor Availability</h2>
        <span style="font-size:11.5px;color:#64748b;font-weight:500"><?php echo date('h:i A'); ?></span>
    </div>
    <div style="padding:12px;display:grid;grid-template-columns:repeat(auto-fill,minmax(175px,1fr));gap:10px">
        <?php if(empty($doctors)): ?>
        <div style="grid-column:1/-1;text-align:center;padding:20px;color:#94a3b8;font-size:13px">No doctors registered.</div>
        <?php else: foreach($doctors as $d):
            $avail = (bool)$d['is_available'];
            $bg     = $avail ? '#f0fdf4' : '#fff5f5';
            $border = $avail ? '#86efac' : '#fca5a5';
            $dot    = $avail ? '#16a34a' : '#dc2626';
            $label  = $avail ? 'Available' : 'Unavailable';
        ?>
        <div style="background:<?php echo $bg;?>;border:1.5px solid <?php echo $border;?>;border-radius:9px;padding:13px;cursor:<?php echo $avail?'pointer':'default';?>;transition:box-shadow .15s"
             <?php if($avail): ?>
             onclick="selectDoctor(<?php echo $d['id'];?>,'<?php echo htmlspecialchars($d['full_name'],ENT_QUOTES);?>','<?php echo htmlspecialchars($d['room']??'',ENT_QUOTES);?>')"
             onmouseenter="this.style.boxShadow='0 4px 12px rgba(22,163,74,.15)'"
             onmouseleave="this.style.boxShadow='none'"
             <?php endif; ?>>
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:8px">
                <div style="width:7px;height:7px;border-radius:50%;background:<?php echo $dot;?>;flex-shrink:0"></div>
                <span style="font-size:11.5px;font-weight:700;color:<?php echo $dot;?>"><?php echo $label; ?></span>
            </div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;margin-bottom:3px"><?php echo htmlspecialchars($d['full_name']); ?></div>
            <div style="font-size:11.5px;color:#64748b"><?php echo $d['room'] ? htmlspecialchars($d['room']) : 'No room set'; ?></div>
            <?php if($d['clocked_in_at'] && $avail): ?>
            <div style="font-size:11px;color:#94a3b8;margin-top:3px">Since <?php echo date('h:i A',strtotime($d['clocked_in_at'])); ?></div>
            <?php endif; ?>
            <?php if($avail): ?>
            <div style="font-size:11px;font-weight:700;margin-top:6px;color:<?php echo $d['patient_count']>0?'#dc2626':'#16a34a';?>">
                <?php echo $d['patient_count']; ?> patient<?php echo $d['patient_count']!=1?'s':''; ?> today
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; endif; ?>
    </div>
</div>