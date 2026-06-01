<?php
session_start();
include 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apt_id'])){
    $apt_id = mysqli_real_escape_string($conn, $_POST['apt_id']);
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);
    $new_issue = mysqli_real_escape_string($conn, $_POST['new_issue']);

    // Database update query
    $sql = "UPDATE appointments SET appointment_date = '$new_date', issue = '$new_issue' WHERE id = '$apt_id'";
    
    if(mysqli_query($conn, $sql)){
        header("Location: patient_dashboard.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>