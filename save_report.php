<?php
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $p_id = $_POST['patient_id'];
    $desc = $_POST['description'];
    
    // File handling
    $filename = time() . "_" . $_FILES['report_file']['name']; // Unique name banane ke liye time joda
    $tempname = $_FILES['report_file']['tmp_name'];
    $folder = "uploads/" . $filename;

    // Database mein entry
    $sql = "INSERT INTO reports (patient_id, file_path, description) VALUES ('$p_id', '$folder', '$desc')";
    
    if (move_uploaded_file($tempname, $folder)) {
        mysqli_query($conn, $sql);
        echo "<script>alert('Report Uploaded Successfully!'); window.location='doctor_dashboard.php';</script>";
    } else {
        echo "Failed to upload.";
    }
}
?>