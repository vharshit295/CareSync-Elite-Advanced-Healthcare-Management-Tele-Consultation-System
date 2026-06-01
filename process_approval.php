<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $q = "UPDATE users SET status='approved' WHERE id='$id'";
        $m = "Doctor Verified Successfully!";
    } else {
        $q = "UPDATE users SET status='rejected' WHERE id='$id'";
        $m = "Registration Denied!";
    }

    mysqli_query($conn, $q);
    echo "<script>alert('$m'); window.location.href='admin_dashboard.php';</script>";
}
?>