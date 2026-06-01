<?php
session_start();
include 'db.php';

// Check agar doctor logged in hai
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'doctor'){ 
    header("Location: login.php"); exit(); 
}

if(isset($_POST['submit_prescription'])) {
    $app_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $p_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doc_id = $_SESSION['user_id'];
    $notes = mysqli_real_escape_string($conn, $_POST['medicine_notes']);
    
    // File Upload Logic
    $file_name = NULL;
    if(!empty($_FILES['patient_report']['name'])) {
        // Folder check: Agar 'uploads' folder nahi hai to bana do
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        $file_name = time() . "_" . basename($_FILES['patient_report']['name']);
        $target_path = "uploads/" . $file_name;
        
        if(!move_uploaded_file($_FILES['patient_report']['tmp_name'], $target_path)) {
            echo "<script>alert('File upload fail ho gayi!');</script>";
        }
    }

    // Reports table mein data save karna
    // Make sure aapne database mein 'ALTER TABLE reports ADD report_file VARCHAR(255)' run kar diya hai
    $query = "INSERT INTO reports (appointment_id, patient_id, doctor_id, prescription_text, report_file) 
              VALUES ('$app_id', '$p_id', '$doc_id', '$notes', '$file_name')";
    
    if(mysqli_query($conn, $query)) {
        // Appointment ko complete mark karna taaki queue se hat jaye
        mysqli_query($conn, "UPDATE appointments SET status='completed' WHERE id='$app_id'");
        
        echo "<script>alert('Prescription & Report Saved Successfully!'); window.location.href='doctor_dashboard.php';</script>";
    } else {
        // Agar yahan 'Unknown column' error aaye, to SQL mein column add karein
        echo "Database Error: " . mysqli_error($conn);
    }
}
?>