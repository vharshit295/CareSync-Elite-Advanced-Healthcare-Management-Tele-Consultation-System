<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// --- NEW BROADCAST LOGIC (KEEPING IT AS IT IS) ---
if (isset($_POST['send_broadcast'])) {
    $title = mysqli_real_escape_string($conn, $_POST['broadcast_title']);
    $msg = mysqli_real_escape_string($conn, $_POST['broadcast_msg']);
    $target = mysqli_real_escape_string($conn, $_POST['target_role']);

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS announcements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        message TEXT,
        target_role ENUM('doctor', 'patient', 'both') DEFAULT 'both',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $sql = "INSERT INTO announcements (title, message, target_role) VALUES ('$title', '$msg', '$target')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Broadcast Sent Successfully!'); window.location='admin_dashboard.php';</script>";
    }
}

// 1. Live Stats Calculation
$docs_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='doctor' AND status='approved'"))['t'];
$pats_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM users WHERE role='patient'"))['t'];
$queries_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM appointments WHERE status='pending'"))['t'];

// 2. Fetch Data
$pending_docs = mysqli_query($conn, "SELECT * FROM users WHERE role='doctor' AND status='pending'");
$pending_num = mysqli_num_rows($pending_docs); 
$verified_docs = mysqli_query($conn, "SELECT * FROM users WHERE role='doctor' AND status='approved'");
$all_patients = mysqli_query($conn, "SELECT * FROM users WHERE role='patient'");
$feedbacks = mysqli_query($conn, "SELECT * FROM feedback ORDER BY id DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareSync Elite | Admin HQ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        /* AAPKE PURANE STYLES - NO CHANGE */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');
        :root { --sidebar-bg: #0f172a; --main-bg: #f8fafc; --accent: #0ea5e9; --accent-glow: rgba(14, 165, 233, 0.3); --red: #f43f5e; --green: #10b981; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        body { background: var(--main-bg); display: flex; overflow: hidden; }
        .sidebar { width: 280px; background: var(--sidebar-bg); height: 100vh; padding: 35px 20px; position: fixed; z-index: 1000; }
        .brand { display: flex; align-items: center; gap: 15px; margin-bottom: 50px; }
        .brand i { font-size: 28px; color: var(--accent); background: rgba(14, 165, 233, 0.15); padding: 12px; border-radius: 15px; box-shadow: 0 0 20px var(--accent-glow); }
        .brand h2 { font-size: 20px; font-weight: 800; color: white; letter-spacing: 1px; }
        .admin-tag { font-size: 10px; color: var(--accent); font-weight: 800; letter-spacing: 3px; text-transform: uppercase; display: block; margin-top: -2px; }
        .sidebar ul li { list-style: none; margin-bottom: 8px; }
        .sidebar ul li a { color: #94a3b8; text-decoration: none; display: flex; align-items: center; gap: 15px; padding: 15px; border-radius: 14px; }
        .sidebar ul li a:hover { background: rgba(255,255,255,0.05); color: white; transform: translateX(8px); }
        .badge { background: var(--red); color: white; padding: 2px 8px; border-radius: 50px; font-size: 11px; animation: pulse 2s infinite; }
        .main { margin-left: 280px; padding: 40px; width: calc(100% - 280px); height: 100vh; overflow-y: auto; scroll-behavior: smooth; position: relative; }
        .broadcast-card { background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 30px; border-radius: 24px; margin-bottom: 40px; border-left: 6px solid var(--accent); }
        .broadcast-card input, .broadcast-card textarea, .broadcast-card select { width: 100%; margin: 8px 0; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; color: white; padding: 12px; outline: none; }
        .broadcast-card select option { background: #1e293b; color: white; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 20px; border-bottom: 4px solid var(--accent); box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
        .mgmt-section { background: white; padding: 30px; border-radius: 24px; margin-bottom: 35px; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        th { text-align: left; padding: 10px 15px; color: #94a3b8; font-size: 12px; text-transform: uppercase; }
        td { padding: 18px 15px; background: #fff; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; }
        td:first-child { border-left: 1px solid #f1f5f9; border-radius: 12px 0 0 12px; }
        td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 12px 12px 0; }
        .glow-name { font-weight: 700; }
        .btn { padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 13px; }
        .btn-approve { background: var(--green); color: white; }
        .btn-deny { background: var(--red); color: white; }
        .btn-delete { color: var(--red); background: rgba(244, 63, 94, 0.1); width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; text-decoration: none; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-hand-holding-medical"></i>
            <div>
                <h2>CARESYNC</h2>
                <span class="admin-tag">Admin Panel</span>
            </div>
        </div>
        <ul>
            <li><a href="#dashboard-top"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="#broadcast"><i class="fas fa-bullhorn"></i> Announcement</a></li>
            <li><a href="#new-docs"><i class="fas fa-user-clock"></i> New Approvals <?php if($pending_num > 0) echo "<span class='badge'>$pending_num</span>"; ?></a></li>
            <li><a href="#feedback-hub"><i class="fas fa-star"></i> Feedbacks</a></li>
            <li><a href="#verified-docs"><i class="fas fa-user-md"></i> Doctors</a></li>
            <li><a href="#patients"><i class="fas fa-users"></i> Patients</a></li>
            <li style="margin-top: 50px;"><a href="logout.php" style="color: var(--red);"><i class="fas fa-power-off"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div id="dashboard-top" style="position: absolute; top: 0; left: 0;"></div>
        <h1 class="animate__animated animate__fadeInDown">Administrator Command Center</h1>
        <p style="color: gray; margin-bottom: 35px;">Welcome, Harshit & Ayush | System Monitoring Active</p>

        <div class="stats-grid">
            <div class="stat-card"><p>Doctors</p><h3><?php echo $docs_count; ?></h3><span style="color:var(--green); font-size:10px;">● Verified</span></div>
            <div class="stat-card" style="border-color: var(--green);"><p>Patients</p><h3><?php echo $pats_count; ?></h3></div>
            <div class="stat-card" style="border-color: #f59e0b;"><p>Pending</p><h3><?php echo $queries_count; ?></h3></div>
            <div class="stat-card" style="border-color: #8b5cf6;"><p>Live</p><h3><?php echo rand(10, 40); ?></h3></div>
        </div>

        <div class="broadcast-card" id="broadcast">
            <h3><i class="fas fa-bolt"></i> Global Broadcast</h3>
            <form method="POST">
                <input type="text" name="broadcast_title" placeholder="Announcement Title" required>
                <textarea name="broadcast_msg" placeholder="Write a message..." required></textarea>
                <div style="display:flex; gap:10px; align-items:center; margin-top:5px;">
                    <label style="font-size:13px; min-width:80px;">Target:</label>
                    <select name="target_role">
                        <option value="both">Both (Doctors & Patients)</option>
                        <option value="doctor">Doctors Only</option>
                        <option value="patient">Patients Only</option>
                    </select>
                </div>
                <button type="submit" name="send_broadcast" class="btn" style="background: var(--accent); color: white; margin-top:15px; width:100%;">Send Broadcast Now</button>
            </form>
        </div>

        <div class="mgmt-section" id="feedback-hub">
    <h3><i class="fas fa-comment-alt"></i> Recent User Feedback</h3>

    <div class="feedback-grid" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:15px;">
        
        <?php while($f = mysqli_fetch_assoc($feedbacks)) { ?>
        
        <div class="feedback-card" style="background:#f8fafc; padding:15px; border-radius:15px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            
            <!-- Name + Role -->
            <div style="display:flex; justify-content:space-between;">
                <b><?php echo $f['name']; ?></b>
                <small style="color:var(--accent);">
                    <?php echo strtoupper($f['role']); ?>
                </small>
            </div>

            <!-- ⭐ Rating -->
            <div style="color:gold; margin:5px 0;">
                <?php 
                for($i=1; $i<=5; $i++){
                    if($i <= $f['rating']){
                        echo "★";
                    } else {
                        echo "☆";
                    }
                }
                ?>
            </div>

            <!-- Message -->
            <p style="font-size:12px; margin-top:5px;">
                "<?php echo $f['message']; ?>"
            </p>

            <!-- Date -->
            <small style="color:gray;">
                <?php echo date("d M Y, h:i A", strtotime($f['created_at'])); ?>
            </small>

        </div>

        <?php } ?>

    </div>
</div>

        <div class="mgmt-section" id="new-docs">
            <h3><i class="fas fa-clock"></i> Pending Doctor Approvals</h3>
            <table>
                <tr><th>Doctor Name</th><th>License</th><th>Specialization</th><th>Action</th></tr>
                <?php while($row = mysqli_fetch_assoc($pending_docs)) { ?>
                <tr>
                    <td><b class="glow-name">Dr. <?php echo $row['name']; ?></b></td>
                    <td><code><?php echo (!empty($row['license_no'])) ? $row['license_no'] : 'MC-PENDING'; ?></code></td>
                    <td><?php echo $row['specialization'] ?? 'Surgeon'; ?></td>
                    <td>
                        <form action="process_approval.php" method="POST" style="display:flex; gap:10px;">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button name="action" value="approve" class="btn btn-approve">Approve</button>
                            <button name="action" value="deny" class="btn btn-deny">Deny</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div class="mgmt-section" id="verified-docs">
            <h3><i class="fas fa-check-double"></i> Verified Medical Staff</h3>
            <table>
                <tr><th>Doctor</th><th>License</th><th>Specialization</th><th>Action</th></tr>
                <?php while($d = mysqli_fetch_assoc($verified_docs)) { ?>
                <tr>
                    <td><b class="glow-name">Dr. <?php echo $d['name']; ?></b></td>
                    <td><code><?php echo (!empty($d['license_no'])) ? $d['license_no'] : 'MC-PENDING'; ?></code></td>
                    <td><?php echo $d['specialization']; ?></td>
                    <td><a href="delete_user.php?id=<?php echo $d['id']; ?>" class="btn-delete"><i class="fas fa-trash-alt"></i></a></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div class="mgmt-section" id="patients">
            <h3><i class="fas fa-user-injured"></i> Patient Records</h3>
            <table>
                <tr><th>Name</th><th>Email ID</th><th>Status</th><th>Action</th></tr>
                <?php while($p = mysqli_fetch_assoc($all_patients)) { ?>
                <tr>
                    <td><b class="glow-name"><?php echo $p['name']; ?></b></td>
                    <td><?php echo $p['email']; ?></td>
                    <td><span style="color:var(--green); font-weight:bold;">REGISTERED</span></td>
                    <td><a href="delete_user.php?id=<?php echo $p['id']; ?>" class="btn-delete"><i class="fas fa-user-minus"></i></a></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>