<?php
session_start();
include 'db.php';
$doc_id = $_SESSION['user_id'];
$history = mysqli_query($conn, "SELECT a.*, u.name as pname, p.medicine FROM appointments a JOIN users u ON a.patient_id = u.id LEFT JOIN prescriptions p ON a.id = p.appointment_id WHERE a.doctor_id = '$doc_id' AND a.status = 'completed' ORDER BY a.id DESC");
?>