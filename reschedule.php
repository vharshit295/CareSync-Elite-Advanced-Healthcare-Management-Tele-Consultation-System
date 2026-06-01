<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient'){
    header("Location: login.php"); exit();
}

$p_id = $_SESSION['user_id'];

if(isset($_GET['id'])){
    $appt_id = mysqli_real_escape_string($conn, $_GET['id']);
    // Yahan columns ko database schema ke hisaab se fetch kiya gaya hai
    $query = "SELECT a.*, u.name as doctor_name FROM appointments a 
              JOIN users u ON a.doctor_id = u.id 
              WHERE a.id = '$appt_id' AND a.patient_id = '$p_id'";
    $result = mysqli_query($conn, $query);
    $appt = mysqli_fetch_assoc($result);

    if(!$appt){
        die("Appointment not found!");
    }
}

if(isset($_POST['reschedule'])){
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);
    $new_problem = mysqli_real_escape_string($conn, $_POST['new_problem']);
    
    // ERROR FIX: 'date' ko 'app_date' kiya aur 'problem' ko 'problem_desc'
    $update_sql = "UPDATE appointments SET app_date='$new_date', problem_desc='$new_problem' 
                   WHERE id='$appt_id' AND patient_id='$p_id'";
    
    if(mysqli_query($conn, $update_sql)){
        echo "<script>alert('Updated Successfully!'); window.location.href='patient_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reschedule | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e74c3c; --secondary: #3498db; --dark: #2c3e50; --light-bg: #f4f7f6; }
        body { font-family: 'Poppins', sans-serif; background: var(--light-bg); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reschedule-card { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 90%; max-width: 400px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 12px; margin-bottom: 20px; border-left: 4px solid var(--secondary); }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; margin-bottom: 20px; box-sizing: border-box; }
        .save-btn { width: 100%; background: var(--primary); color: white; border: none; padding: 12px; border-radius: 10px; cursor: pointer; font-weight: 600; }
    </style>
</head>
<body>

<div class="reschedule-card">
    <h2 style="text-align:center;">Reschedule</h2>
    
    <div class="info-box">
        <small>Doctor:</small>
        <div style="font-weight: bold;">Dr. <?php echo htmlspecialchars($appt['doctor_name'] ?? 'Doctor'); ?></div>
    </div>

    <form method="POST">
        <label style="font-size: 13px; font-weight: 600;">New Date</label>
        <input type="date" name="new_date" value="<?php echo $appt['app_date'] ?? ''; ?>" required>

        <label style="font-size: 13px; font-weight: 600;">Problem Description</label>
        <textarea name="new_problem" rows="3" required><?php echo htmlspecialchars($appt['problem_desc'] ?? ''); ?></textarea>

        <button type="submit" name="reschedule" class="save-btn">Save Changes</button>
        <div style="text-align:center; margin-top:15px;">
            <a href="patient_dashboard.php" style="color:#7f8c8d; text-decoration:none; font-size:14px;">Go Back</a>
        </div>
    </form>
</div>

</body>
</html>