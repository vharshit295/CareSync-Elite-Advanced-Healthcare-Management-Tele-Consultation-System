<?php
include 'db.php'; // Database connection

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = $_POST['doc_id'];
    $days = $_POST['available_days'];
    $time = $_POST['available_time'];
    $address = $_POST['clinic_address'];

    // Database mein update karne ki command
    $sql = "UPDATE users SET available_days='$days', available_time='$time', clinic_address='$address' WHERE id=$id";
    
    if(mysqli_query($conn, $sql)){
        // Wapas dashboard par bhej do
        header("Location: admin_dashboard.php");
    } else {
        echo "Galti ho gayi: " . mysqli_error($conn);
    }
}
?>