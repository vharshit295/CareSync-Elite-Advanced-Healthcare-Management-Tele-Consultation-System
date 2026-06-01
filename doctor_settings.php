<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'doctor'){ 
    header("Location: login.php"); exit(); 
}

$doc_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$doc_id'"));

// Pehle se selected days ko array mein convert karna
$selected_days = explode(", ", $user['available_days']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Settings | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 550px; background: white; margin: 20px auto; padding: 30px; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.1); }
        .profile-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #e74c3c; display: block; margin: 0 auto 15px; }
        label { font-weight: bold; color: #2c3e50; display: block; margin-top: 15px; }
        input[type="text"], input[type="password"], input[type="file"] { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .days-box { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 5px; }
        .btn-save { background: #e74c3c; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 25px; font-size: 16px; }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align:center; color:#2c3e50;"><i class="fas fa-user-cog"></i> Profile Settings</h2>
    
    <?php 
        $img_path = !empty($user['profile_pic']) ? "uploads/".$user['profile_pic'] : "https://cdn-icons-png.flaticon.com/512/1053/1053244.png";
    ?>
    <img src="<?php echo $img_path; ?>" class="profile-img">

    <form action="process_doctor_update.php" method="POST" enctype="multipart/form-data">
        
        <label>Upload New Profile Photo:</label>
        <input type="file" name="profile_pic" accept="image/*">

        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

        <label>Update Password (Khaali chhodein agar nahi badalna):</label>
        <input type="password" name="password" placeholder="New Password">

        <label>Clinic Address:</label>
        <input type="text" name="clinic_address" value="<?php echo $user['clinic_address']; ?>" required>

        <label>Shift Timings (e.g. 10:00 AM - 04:00 PM):</label>
        <input type="text" name="shift_time" value="<?php echo $user['shift_time']; ?>" required>

        <label>Available Days (Tick Karein):</label>
        <div class="days-box">
            <?php 
            $weekdays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
            foreach($weekdays as $day){
                $checked = in_array($day, $selected_days) ? "checked" : "";
                echo "<span><input type='checkbox' name='days[]' value='$day' $checked> $day</span>";
            }
            ?>
        </div>

        <button type="submit" name="update_all" class="btn-save">Save All Changes</button>
    </form>
    <a href="doctor_dashboard.php" style="display:block; text-align:center; margin-top:15px; color:gray; text-decoration:none;">Back to Dashboard</a>
</div>

</body>
</html>