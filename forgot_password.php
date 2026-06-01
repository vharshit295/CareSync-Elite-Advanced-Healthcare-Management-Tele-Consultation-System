<?php
include 'db.php';

if(isset($_POST['reset_request'])){
    $user_input = mysqli_real_escape_string($conn, $_POST['user_input']);
    $new_pass = $_POST['new_password'];
    
    // Check karna ki ye user exist karta hai ya nahi
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$user_input' OR phone='$user_input'");
    
    if(mysqli_num_rows($check) > 0){
        // Password update query
        $update = "UPDATE users SET password='$new_pass' WHERE email='$user_input' OR phone='$user_input'";
        if(mysqli_query($conn, $update)){
            echo "<script>alert('Password Reset Successful! Ab login karein.'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Error: Ye Email ya Phone hamare record mein nahi hai!');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI'; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
        .container { background: white; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 400px; padding: 40px; text-align: center; }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        input, button { width: 100%; padding: 12px; margin: 10px 0; border-radius: 10px; border: 1px solid #ddd; outline: none; box-sizing: border-box; }
        button { background: #3498db; color: white; border: none; cursor: pointer; font-weight: bold; }
        .back-link { margin-top: 15px; display: block; color: #7f8c8d; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <i class="fas fa-lock-open" style="font-size: 50px; color: #3498db; margin-bottom: 20px;"></i>
        <h2>Reset Password</h2>
        <form method="POST">
            <input type="text" name="user_input" placeholder="Registered Email or Phone" required>
            <input type="password" name="new_password" placeholder="Enter New Password" required minlength="4">
            <button name="reset_request">Update Password</button>
        </form>
        <a href="login.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Login</a>
    </div>
</body>
</html>