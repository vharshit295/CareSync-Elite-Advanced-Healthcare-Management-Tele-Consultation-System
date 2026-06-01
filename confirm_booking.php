<?php
session_start();
include 'db.php';

if(isset($_POST['doctor_id'])) {
    $p_id = $_SESSION['user_id'];
    $doc_id = $_POST['doctor_id'];
    $problem = mysqli_real_escape_with_mysqli($conn, $_POST['problem_desc']);
    $app_date = $_POST['app_date']; // User dwara select ki gayi date
    $hide_identity = isset($_POST['hide_identity']) ? 1 : 0;

    // 1. Aaj ke din is doctor ke liye sabse bada token number dhundna
    $token_q = "SELECT MAX(token_number) as max_token FROM appointments WHERE doctor_id = '$doc_id' AND app_date = '$app_date'";
    $token_res = mysqli_query($conn, $token_q);
    $token_row = mysqli_fetch_assoc($token_res);
    
    // 2. Naya token set karna (Agar koi nahi hai toh 1, nahi toh +1)
    $new_token = ($token_row['max_token']) ? $token_row['max_token'] + 1 : 1;

    // 3. Appointment save karna
    $query = "INSERT INTO appointments (patient_id, doctor_id, problem_desc, app_date, token_number, status, hide_identity) 
              VALUES ('$p_id', '$doc_id', '$problem', '$app_date', '$new_token', 'pending', '$hide_identity')";

    if(mysqli_query($conn, $query)) {
        echo "<script>
                alert('Appointment Booked Successfully! Your Token: #$new_token');
                window.location.href='patient_dashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Helper function agar direct use kar rahe ho
function mysqli_real_escape_with_mysqli($conn, $data) {
    return mysqli_real_escape_string($conn, $data);
}
?>