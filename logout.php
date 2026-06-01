<?php
session_start();

// Check if session exists
if(isset($_SESSION['user_id']) && isset($_SESSION['user_role'])){

    $role = $_SESSION['user_role'];

    // Sirf Doctor aur Patient ko feedback page par bhejna hai
    if($role == 'Doctor' || $role == 'Patient'){
        header("Location: feedback.php");
        exit();
    }
}

// Admin ya koi aur case → direct logout
session_destroy();
header("Location: index.php");
exit();
?>