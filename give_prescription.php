<?php
session_start();
include 'db.php';

// Auth Check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'doctor'){ 
    header("Location: login.php"); exit(); 
}

// Check if app_id is set in URL
if(!isset($_GET['app_id'])) {
    die("Error: Appointment ID missing.");
}

$app_id = mysqli_real_escape_string($conn, $_GET['app_id']);

// Database se patient_id nikalna taaki error na aaye
$res = mysqli_query($conn, "SELECT patient_id FROM appointments WHERE id='$app_id'");
if(mysqli_num_rows($res) > 0){
    $data = mysqli_fetch_assoc($res);
    $p_id = $data['patient_id'];
} else {
    die("Error: Appointment record not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write Prescription | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 40px; display: flex; justify-content: center; }
        .box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        textarea { width: 100%; height: 100px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 10px; resize: none; font-size: 14px; }
        .btn { background: #27ae60; color: white; border: none; padding: 12px; border-radius: 8px; width: 100%; cursor: pointer; font-size: 16px; margin-top: 20px; font-weight: bold; }
        .report-section { background: #f0f7ff; padding: 15px; border-radius: 10px; border: 1px dashed #3498db; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="box">
        <h2><i class="fas fa-prescription"></i> Write Prescription</h2>
        
        <form action="save_prescription.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="appointment_id" value="<?php echo $app_id; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $p_id; ?>">

            <p><b>Medicine Details & Advice:</b></p>
            <textarea name="medicine_notes" placeholder="Write medicines and advice here..." required></textarea>

            <div class="report-section">
                <label style="font-weight:bold; color:#2980b9;">
                    <i class="fas fa-file-medical"></i> Upload Patient Report (Optional):
                </label>
                <input type="file" name="patient_report" accept=".pdf, image/*" style="margin-top:10px;">
                <p style="font-size:11px; color:gray;">*PDF or Image only.</p>
            </div>

            <button type="submit" name="submit_prescription" class="btn">Save & Close Case</button>
        </form>
    </div>
</body>
</html>