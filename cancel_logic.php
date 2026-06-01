<?php
session_start();
include 'db.php';

$p_id = $_SESSION['user_id'];

// 1. UPDATE APPOINTMENT DATE
if(isset($_POST['update_apt'])){
    $apt_id = $_POST['apt_id'];
    $new_date = $_POST['new_date'];
    mysqli_query($conn, "UPDATE appointments SET date = '$new_date' WHERE id = '$apt_id' AND patient_id = '$p_id'");
    header("Location: patient_dashboard.php?msg=Updated");
}

// 2. CANCEL/CLOSE APPOINTMENT
if(isset($_GET['id'])){
    $apt_id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM appointments WHERE id = '$apt_id' AND patient_id = '$p_id'");
    header("Location: patient_dashboard.php?msg=Cancelled");
}
?>