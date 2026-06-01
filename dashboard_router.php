<?php
// User ne jo role select kiya tha uske hisaab se redirect karega
$role = $_POST['role']; 

if ($role == 'patient') {
    header("Location: patient_dashboard.php");
} else {
    header("Location: doctor_dashboard.php");
}
?>