<?php
session_start();
include 'db.php';

date_default_timezone_set('Asia/Kolkata'); 

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'doctor'){ 
    header("Location: login.php"); 
    exit(); 
}

$doc_id = $_SESSION['user_id'];

// --- UPDATE KIYI HUYI QUERY ---
$check_status = mysqli_query($conn, "SELECT name, status, profile_complete, specialization FROM users WHERE id='$doc_id'");
$user = mysqli_fetch_assoc($check_status);

if($user['profile_complete'] == 0){
    header("Location: complete_profile.php"); 
    exit();
}

if($user['status'] == 'pending'){
    echo "<div style='text-align:center; margin-top:100px; font-family:sans-serif; background:#fdf2f2; padding:50px; border-radius:15px; width:50%; margin:auto; border: 1px solid #e74c3c;'>
            <h1 style='color:#e74c3c;'>Approval Pending! ⏳</h1>
            <p style='font-size:18px;'>Hi Dr. <strong>" . $user['name'] . "</strong>, Your account is currently under review.</p>
            <p>The dashboard will open as soon as the admin approves. Until then, please wait..</p>
            <br>
            <a href='logout.php' style='background:#e74c3c; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Logout</a>
          </div>";
    exit();
}
// ---------------------------------
// 3. Date Logic
$today = date('Y-m-d'); 

// 4. Stats: Aaj kitne patients hain (Sirf count ke liye)
$stats_q = mysqli_query($conn, "SELECT COUNT(*) as today_count FROM appointments WHERE doctor_id = '$doc_id' AND app_date = '$today' AND status='pending'");
$stats = mysqli_fetch_assoc($stats_q);

// 5. FINAL RESULT QUERY: Ye line badli hai (>= $today) 
// Isse agar patient ne kal ki date bhi select ki hogi, toh bhi doctor ko dikhega!
$result = mysqli_query($conn, "SELECT * FROM appointments 
    WHERE doctor_id = '$doc_id' 
    AND app_date >= '$today' 
    AND status = 'pending' 
    ORDER BY app_date ASC, token_number ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e74c3c; --dark: #2c3e50; --bg: #f4f7f6; --blue: #3498db; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; display: flex; }
        .sidebar { width: 260px; background: var(--dark); color: white; height: 100vh; position: fixed; padding: 25px; box-sizing: border-box; }
        .sidebar h2 { color: var(--primary); margin-bottom: 30px; }
        .sidebar a { display: block; color: #bdc3c7; padding: 12px; text-decoration: none; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; }
        .main { margin-left: 260px; padding: 30px; width: 100%; }
        .header-box { background: white; padding: 25px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .token-badge { background: #fdf2f2; color: var(--primary); padding: 6px 14px; border-radius: 50px; font-weight: bold; }
        table { width: 100%; background: white; border-radius: 15px; border-collapse: collapse; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th { background: #f8f9fa; padding: 18px; text-align: left; color: #7f8c8d; }
        td { padding: 18px; border-bottom: 1px solid #f1f1f1; }
        .btn { padding: 8px 16px; border-radius: 6px; color: white; text-decoration: none; font-size: 12px; font-weight: bold; background: var(--primary); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>CareSync</h2>
        <div style="margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 10px;">
            <p style="margin:0; font-weight:bold;">Dr. <?php echo $user['name']; ?></p>
            <small style="color:#95a5a6;"><?php echo $user['specialization']; ?></small>
        </div>
        <a href="doctor_dashboard.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="doctor_settings.php"><i class="fas fa-user-cog"></i> Settings</a>
        <a href="logout.php" style="margin-top:50px; background:var(--primary); color:white; text-align:center;"><i class="fas fa-power-off"></i> Logout</a>
    </div>

    <div class="main">
        <div class="header-box">
            <div>
                <h2 style="margin:0;">Patient Queue</h2>
                <p style="color:gray; margin:5px 0;">Today: <?php echo date('d M, Y'); ?></p> </div>
            <div style="text-align:right;">
                <h1 style="margin:0; color:var(--primary);"><?php echo $stats['today_count']; ?></h1>
                <small>Pending Patients</small>
            </div>
        </div>

        <table>
            <thead>
                <tr><th>TOKEN</th><th>PATIENT</th><th>ISSUE</th><th style="text-align:center;">ACTION</th></tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { 
                        $p_id = $row['patient_id'];
                        $p_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$p_id'"));
                ?>
                <tr>
                    <td><span class="token-badge">#<?php echo $row['token_number']; ?></span></td>
                    <td>
                        <?php if($row['hide_identity'] == 1): ?>
                            <i class='fas fa-user-secret'></i> <span style='color:gray; font-style:italic;'>Anonymous Patient</span>
                        <?php else: ?>
                            <strong><?php echo $p_info['name']; ?></strong><br>
                            <small><?php echo $p_info['gender']; ?>, <?php echo $p_info['age']; ?> Yrs</small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['problem_desc']; ?></td>
                    <td style="text-align:center;">
                        <a href="give_prescription.php?app_id=<?php echo $row['id']; ?>" class="btn">Prescribe</a>
                        <a href="video_call.php?id=<?php echo $row['id']; ?>" target="_blank" 
                           style="background: #2ecc71; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; margin-left: 5px;">
                           <i class="fas fa-video"></i> Join Call
                        </a>
                    </td>
                </tr>
                <?php } } else { ?>
                <tr><td colspan="4" style="text-align:center; padding:50px; color:gray;">No pending patients for today.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>