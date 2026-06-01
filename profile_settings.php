<?php
session_start();
include 'db.php';

// Agar login nahi hai toh login page par bhejo [cite: 2026-01-21]
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];

// Profile Update Logic
if(isset($_POST['update'])){
    $new_pass = $_POST['password'];
    $new_shift = $_POST['shift_time']; // Ye naye column mein jayega

    $sql = "UPDATE users SET password='$new_pass', shift_time='$new_shift' WHERE id='$user_id'";
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Profile Updated Successfully!'); window.location='doctor_dashboard.php';</script>";
    }
}
?>

<form method="POST" style="padding: 20px; background: white; border-radius: 10px; width: 400px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
    <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
    <label>New Password:</label>
    <input type="password" name="password" required style="width:100%; padding:10px; margin:10px 0;">
    
    <label>Select Your Shift:</label>
    <select name="shift_time" style="width:100%; padding:10px; margin:10px 0;">
        <option value="09:00 AM - 02:00 PM">Morning (9AM-2PM)</option>
        <option value="02:00 PM - 07:00 PM">Evening (2PM-7PM)</option>
        <option value="08:00 PM - 02:00 AM">Night (8PM-2AM)</option>
    </select>
    
    <button name="update" style="background:#3498db; color:white; border:none; padding:12px; width:100%; cursor:pointer; font-weight:bold;">Update Now</button>
</form>