<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){ exit("Unauthorized"); }
$p_id = $_SESSION['user_id'];

// 1. UPDATE LOGIC
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_apt'])){
    $apt_id = mysqli_real_escape_string($conn, $_POST['apt_id']);
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);
    $new_issue = mysqli_real_escape_string($conn, $_POST['new_issue']);

    $sql = "UPDATE appointments SET appointment_date = '$new_date', issue = '$new_issue' WHERE id = '$apt_id' AND patient_id = '$p_id'";
    if(mysqli_query($conn, $sql)) header("Location: patient_dashboard.php?msg=updated");
}

// 2. DELETE/CANCEL LOGIC
if(isset($_GET['cancel_id'])){
    $apt_id = mysqli_real_escape_string($conn, $_GET['cancel_id']);
    $sql = "DELETE FROM appointments WHERE id = '$apt_id' AND patient_id = '$p_id'";
    if(mysqli_query($conn, $sql)) header("Location: patient_dashboard.php?msg=cancelled");
}
?>