<?php
session_start();
include 'db.php';
if(isset($_POST['change_pass'])){
    $new_p = $_POST['new_password'];
    $uid = $_SESSION['user_id'];
    $sql = "UPDATE users SET password='$new_p' WHERE id='$uid'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Security Protocol: Password Updated!'); window.location='logout.php';</script>";
    }
}
?>