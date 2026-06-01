<?php
session_start();
include 'db.php';

$app_id = $_GET['app_id']; // URL se appointment ID uthayega

// Patient ki details fetch karo
$query = mysqli_query($conn, "SELECT a.*, u.name FROM appointments a JOIN users u ON a.patient_id = u.id WHERE a.id = '$app_id'");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['submit_prescription'])){
    $medicine = mysqli_real_escape_string($conn, $_POST['medicine']);
    $advice = mysqli_real_escape_string($conn, $_POST['advice']);
    $doc_id = $_SESSION['user_id'];
    $pat_id = $data['patient_id'];

    // 1. Prescription save karo
    mysqli_query($conn, "INSERT INTO prescriptions (appointment_id, doctor_id, patient_id, medicine_details, advice) 
                        VALUES ('$app_id', '$doc_id', '$pat_id', '$medicine', '$advice')");

    // 2. Appointment status 'completed' kar do
    mysqli_query($conn, "UPDATE appointments SET status='completed' WHERE id='$app_id'");

    header("Location: doctor_dashboard.php?msg=Prescription Sent");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Write Prescription | CareSync</title>
    <style>
        body { font-family: sans-serif; background: #f4f7fe; padding: 40px; }
        .pres-card { background: white; padding: 30px; border-radius: 20px; max-width: 600px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        textarea { width: 100%; height: 100px; margin: 10px 0; padding: 10px; border-radius: 10px; border: 1px solid #ddd; }
        .btn-send { background: #0ea5e9; color: white; padding: 12px 25px; border: none; border-radius: 10px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="pres-card">
        <h2>Prescription for <?php echo $data['name']; ?></h2>
        <p>Token: #<?php echo $data['token_number']; ?></p>
        
        <form method="POST">
            <label>Medicines & Dosage:</label>
            <textarea name="medicine" placeholder="e.g. Paracetamol 500mg - 1-0-1 (After Food)" required></textarea>
            
            <label>Additional Advice:</label>
            <textarea name="advice" placeholder="e.g. Complete bed rest for 2 days"></textarea>
            
            <button type="submit" name="submit_prescription" class="btn-send">Send to Patient</button>
        </form>
    </div>
</body>
</html>