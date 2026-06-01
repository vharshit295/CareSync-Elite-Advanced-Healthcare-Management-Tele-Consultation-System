<?php
session_start();

if(isset($_SESSION['user_id']) && isset($_SESSION['user_role'])){

    $role = strtolower($_SESSION['user_role']); // convert to lowercase

    if($role == 'doctor' || $role == 'patient'){
        header("Location: feedback.php");
        exit();
    }
}

// admin ya invalid case
session_destroy();
header("Location: index.php");
exit();
?>