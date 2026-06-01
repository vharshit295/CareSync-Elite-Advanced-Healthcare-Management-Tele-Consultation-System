<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient'){
    header("Location: login.php"); exit();
}

$p_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// --- Profile Update Logic ---
if(isset($_POST['update_profile'])){
    $u_phone = mysqli_real_escape_string($conn, $_POST['u_phone']);
    $u_pass = $_POST['u_pass'];
    $up_sql = "UPDATE users SET phone='$u_phone'";
    if(!empty($u_pass)){ 
        $hashed = password_hash($u_pass, PASSWORD_DEFAULT);
        $up_sql .= ", password='$hashed'"; 
    }
    mysqli_query($conn, $up_sql . " WHERE id='$p_id'");
    echo "<script>alert('Profile Updated Successfully!');</script>";
}

// --- Cancel Appointment Logic ---
if(isset($_GET['cancel_id'])){
    $cid = $_GET['cancel_id'];
    mysqli_query($conn, "DELETE FROM appointments WHERE id='$cid' AND patient_id='$p_id'");
    echo "<script>window.location.href='patient_dashboard.php';</script>";
}

// Doctors fetch (Sirf Approved)
$doc_query = "SELECT id, name, specialization, clinic_address, available_days, shift_time FROM users WHERE role = 'doctor' AND status = 'approved'";
$doc_result = mysqli_query($conn, $doc_query);

// Latest Appointment check for Video Call
$appt_q = mysqli_query($conn, "SELECT id, token_number FROM appointments WHERE patient_id = '$p_id' AND status = 'pending' ORDER BY id DESC LIMIT 1");
$latest = mysqli_fetch_assoc($appt_q);

// User info (Sidebar Name)
$u_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$p_id'"));
$display_name = $u_info['name'] ?? 'Patient';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e74c3c; --secondary: #3498db; --dark: #2c3e50; --light-bg: #f4f7f6; --green: #2ecc71; }
        
        /* Smooth Page Entrance */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); margin: 0; display: flex; animation: fadeIn 0.8s ease-out; }
        
        .sidebar { width: 260px; background: var(--dark); color: white; height: 100vh; position: fixed; padding: 25px; box-sizing: border-box; transition: 0.3s; z-index: 1000; }
        .sidebar h2 { color: var(--primary); font-size: 24px; font-weight: 700; margin-bottom: 35px; border-bottom: 1px solid #3e4f5f; padding-bottom: 15px; }
        .sidebar a { display: flex; align-items: center; color: #bdc3c7; padding: 12px 15px; text-decoration: none; border-radius: 10px; margin-bottom: 8px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; transform: translateX(8px); }

        .main { margin-left: 260px; padding: 30px; width: calc(100% - 260px); box-sizing: border-box; }
        
        .status-header { background: linear-gradient(135deg, var(--primary), #ff7675); color: white; padding: 15px 25px; border-radius: 15px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3); }
        
        .video-btn { background: var(--green); color: white; text-decoration: none; padding: 15px; border-radius: 15px; display: block; margin-bottom: 25px; text-align: center; font-weight: bold; animation: pulse 2s infinite; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.4); }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.01); } 100% { transform: scale(1); } }

        .doctor-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; margin-bottom: 30px;}
        
        /* CARD EFFECTS */
        .card { background: white; border-radius: 20px; padding: 25px; border: 1px solid #eee; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; }
        .card:hover { transform: translateY(-12px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-color: var(--secondary); }
        
        .patient-tag { position: absolute; top: 15px; right: 15px; background: #e8f4fd; color: var(--secondary); padding: 4px 10px; border-radius: 10px; font-size: 11px; font-weight: bold; }
        
        .doc-icon { width: 60px; height: 60px; background: #f8f9fa; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: var(--secondary); font-size: 25px; transition: 0.3s; }
        .card:hover .doc-icon { background: var(--secondary); color: white; transform: rotateY(180deg); }

        /* BUTTON SHINE EFFECT */
        .book-btn { background: var(--primary); color: white; border: none; width: 100%; padding: 12px; border-radius: 12px; cursor: pointer; font-weight: 600; margin-top: 10px; position: relative; overflow: hidden; transition: 0.3s; }
        .book-btn:hover { background: #c0392b; letter-spacing: 1px; }
        .book-btn::after { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: rgba(255,255,255,0.2); transform: rotate(45deg); transition: 0.5s; pointer-events: none; }
        .book-btn:hover::after { left: 120%; }

        .appt-box { background: white; padding: 20px; border-radius: 15px; margin-bottom: 30px; border: 1px solid #eee; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .appt-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f9f9f9; transition: 0.2s; }
        .appt-row:hover { background: #fcfcfc; padding-left: 5px; }

        .config-bar { background: white; padding: 15px 20px; border-radius: 15px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #eee; }
        
        input[type="text"], input[type="password"], input[type="date"], textarea { transition: 0.3s; border: 1px solid #ddd; }
        input:focus, textarea:focus { border-color: var(--secondary); outline: none; box-shadow: 0 0 8px rgba(52, 152, 219, 0.2); }

        .switch { position: relative; display: inline-block; width: 40px; height: 22px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary); }
        input:checked + .slider:before { transform: translateX(18px); }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>CareSync</h2>
        <div style="margin-bottom: 30px; padding-left: 10px;">
            <div style="font-size: 12px; color: #95a5a6;">Patient Portal</div>
            <div style="font-weight: 600; font-size: 18px; color:white;"><?php echo htmlspecialchars($display_name); ?></div>
        </div>
        <a href="patient_dashboard.php" class="active"><i class="fas fa-columns"></i> Dashboard</a>
        <a href="my_reports.php"><i class="fas fa-file-medical"></i> My Reports</a>
        <a href="logout.php" style="margin-top: 30px; color: var(--primary);"><i class="fas fa-power-off"></i> Logout System</a>
    </div>

    <div class="main">
        <div class="status-header">
            <span><i class="fas fa-heartbeat"></i> &nbsp;Patient Status Dashboard</span>
            <span>Your Token: <b>#<?php echo $latest['token_number'] ?? '01'; ?></b></span>
        </div>

        <?php if($latest) { ?>
            <a href="video_call.php?id=<?php echo $latest['id']; ?>" target="_blank" class="video-btn">
                <i class="fas fa-video"></i> JOIN LIVE CONSULTATION NOW
            </a>
        <?php } ?>

        <div class="appt-box">
            <h3 style="margin-top:0; font-size:16px;"><i class="fas fa-clock"></i> My Bookings</h3>
            <?php 
            $my_res = mysqli_query($conn, "SELECT a.id, u.name, a.token_number FROM appointments a JOIN users u ON a.doctor_id = u.id WHERE a.patient_id = '$p_id' AND a.status='pending'");
            while($my = mysqli_fetch_assoc($my_res)) { ?>
                <div class="appt-row">
                    <span><b>Dr. <?php echo $my['name']; ?></b> (Token #<?php echo $my['token_number']; ?>)</span>
                    <div>
                        <a href="reschedule.php?id=<?php echo $my['id']; ?>" style="color:var(--secondary); text-decoration:none; margin-right:15px; font-size:13px;"><i class="fas fa-edit"></i> Update</a>
                        <a href="?cancel_id=<?php echo $my['id']; ?>" onclick="return confirm('Cancel this?')" style="color:var(--primary); text-decoration:none; font-size:13px;"><i class="fas fa-trash-alt"></i> Cancel</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="config-bar">
            <form method="POST" style="display:flex; gap:10px; flex:1;">
                <input type="text" name="u_phone" placeholder="Phone" value="<?php echo $u_info['phone'] ?? ''; ?>" style="padding:8px; border-radius:8px; font-size:13px;">
                <input type="password" name="u_pass" placeholder="New Password" style="padding:8px; border-radius:8px; font-size:13px;">
                <button type="submit" name="update_profile" style="background:var(--secondary); color:white; border:none; padding:8px 15px; border-radius:8px; cursor:pointer; font-size:13px;">Update Profile</button>
            </form>
            
            <div style="display:flex; align-items:center; gap:10px; border-left:1px solid #eee; padding-left:20px; margin-left:20px;">
                <span style="font-size:13px; font-weight:600;"><i class="fas fa-user-secret"></i> Anonymous</span>
                <label class="switch">
                    <input type="checkbox" id="anonBtn" onchange="document.querySelectorAll('.is_anon_field').forEach(el => el.value = this.checked ? '1' : '0')">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <h2 style="font-size: 20px; color: var(--dark); margin-bottom: 25px;">Available Specialists</h2>

        <div class="doctor-grid">
            <?php while($doc = mysqli_fetch_assoc($doc_result)) { 
                $d_id = $doc['id'];
                // Update: CURDATE() use kiya real-time count ke liye
                $c_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM appointments WHERE doctor_id = '$d_id' AND app_date = CURDATE() AND status != 'cancelled'"));
            ?>
            <div class="card">
                <div class="patient-tag"><?php echo $c_res['total'] ?? 0; ?> Patient Today</div>
                <div class="doc-icon"><i class="fas fa-user-md"></i></div>
                <h3 style="text-align:center;">Dr. <?php echo htmlspecialchars($doc['name']); ?></h3>
                <div style="color:var(--secondary); font-size:12px; font-weight:700; text-align:center; margin-bottom:15px;"><?php echo htmlspecialchars($doc['specialization']); ?></div>
                
                <div style="font-size:11px; color:#7f8c8d; margin-bottom:8px;"><i class="fas fa-map-marker-alt"></i> <?php echo $doc['clinic_address']; ?></div>
                <div style="font-size:11px; color:#7f8c8d; margin-bottom:8px;"><i class="fas fa-clock"></i> <?php echo $doc['shift_time']; ?> (<?php echo $doc['available_days']; ?>)</div>

                <form action="confirm_booking.php" method="POST" style="margin-top:15px;">
                    <input type="hidden" name="doctor_id" value="<?php echo $doc['id']; ?>">
                    <input type="hidden" name="is_anonymous" class="is_anon_field" value="0">
                    <textarea name="problem_desc" placeholder="What is your health issue?" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #eee; font-size:13px; height:60px; resize:none; margin-bottom:10px;"></textarea>
                    <input type="date" name="app_date" value="<?php echo date('Y-m-d'); ?>" required style="width:100%; padding:10px; border-radius:10px; border:1px solid #eee; margin-bottom:10px;">
                    <button type="submit" class="book-btn">BOOK APPOINTMENT</button>
                </form>
            </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>                                                                                                                                            