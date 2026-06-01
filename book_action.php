<?php
include 'db.php';
$today = date("Y-m-d");
// Ek dummy appointment insert karein
$sql = "INSERT INTO appointments (patient_id, doctor_name, appointment_date, status) 
        VALUES (1, 'Dr. Sharma', '$today', 'Waiting')";

if(mysqli_query($conn, $sql)){
    header("Location: patient_dashboard.php"); // Wapas dashboard bhej do
}
?>