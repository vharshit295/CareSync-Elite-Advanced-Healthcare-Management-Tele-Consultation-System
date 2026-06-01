<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$p_id = $_GET['p_id'] ?? $_SESSION['user_id'];
$patient = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM users WHERE id='$p_id'"));

// Fetching reports from 'reports' table
$res = mysqli_query($conn, "SELECT * FROM reports WHERE patient_id = '$p_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Reports | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e74c3c; --dark: #2c3e50; --bg: #f4f7f6; }
        body { background: var(--bg); font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .container { max-width: 900px; margin: auto; }
        .report-card { background: white; padding: 20px; border-radius: 12px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .btn-download { background: #27ae60; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 13px; }
        .back-btn { display: inline-block; margin-bottom: 20px; color: var(--dark); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <a href="doctor_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2>Reports for: <?php echo $patient['name']; ?></h2>
        <hr><br>

        <?php if(mysqli_num_rows($res) > 0) {
            while($row = mysqli_fetch_assoc($res)) { ?>
            <div class="report-card">
                <div>
                    <i class="fas fa-file-pdf" style="font-size:30px; color:var(--primary); margin-right:15px;"></i>
                    <span style="font-weight:bold;"><?php echo $row['description']; ?></span>
                </div>
                <div>
                    <a href="<?php echo $row['file_path']; ?>" download class="btn-download"><i class="fas fa-download"></i> Download</a>
                    <a href="<?php echo $row['file_path']; ?>" target="_blank" style="margin-left:10px; color:gray;"><i class="fas fa-external-link-alt"></i> Open</a>
                </div>
            </div>
        <?php } } else { echo "<p>No reports found for this patient.</p>"; } ?>
    </div>
</body>
</html>