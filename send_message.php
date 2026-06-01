<?php
session_start();
include 'db.php';

if(isset($_POST['msg']) && isset($_POST['appt_id'])) {
    $appt_id = mysqli_real_escape_string($conn, $_POST['appt_id']);
    $sender_id = $_SESSION['user_id'];
    $msg = mysqli_real_escape_string($conn, $_POST['msg']);

    // Messages table mein data save karna
    $query = "INSERT INTO messages (appointment_id, sender_id, message) VALUES ('$appt_id', '$sender_id', '$msg')";
    mysqli_query($conn, $query);
}
?>