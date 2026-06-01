<?php
session_start();
include 'db.php';

// Security Check: Sirf Admin hi delete kar sakta hai
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

if(isset($_GET['id'])){
    // DHAYAN DEIN: 'mysqli_real_escape_string' sahi function hai
    $user_id = mysqli_real_escape_string($conn, $_GET['id']); 

    $query = "DELETE FROM users WHERE id = '$user_id'";
    
    if(mysqli_query($conn, $query)){
        echo "<script>
                alert('User deleted successfully!');
                window.location.href = 'admin_dashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>