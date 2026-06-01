<?php
session_start();
include 'db.php';

if(isset($_GET['id'])){
    $apt_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Sirf wahi appointment delete ho jo is patient ki ho
    $p_id = $_SESSION['user_id'];
    $sql = "DELETE FROM appointments WHERE id = '$apt_id' AND patient_id = '$p_id'";
    
    if(mysqli_query($conn, $sql)){
        header("Location: patient_dashboard.php?status=deleted");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>