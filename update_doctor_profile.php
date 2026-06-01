<?php
session_start();
include 'db.php';

if (isset($_POST['update_onboarding'])) {
    $doc_id = $_SESSION['user_id'];
    $license = mysqli_real_escape_string($conn, $_POST['license_no']);
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $address = mysqli_real_escape_string($conn, $_POST['clinic_address']);
    
    // Days Checkboxes ko comma se jodna
    $days_array = $_POST['days'] ?? [];
    $days_string = implode(", ", $days_array); 
    
    // Time format karke 'shift_time' mein save karna
    $start = date("h:i A", strtotime($_POST['start_time']));
    $end = date("h:i A", strtotime($_POST['end_time']));
    $final_shift = $start . " - " . $end;

    // Sahi column 'shift_time' use kiya hai
    $query = "UPDATE users SET 
              license_no = '$license', 
              specialization = '$spec', 
              clinic_address = '$address', 
              available_days = '$days_string', 
              shift_time = '$final_shift' 
              WHERE id = '$doc_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Details Submitted Successfully!'); window.location.href='doctor_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>