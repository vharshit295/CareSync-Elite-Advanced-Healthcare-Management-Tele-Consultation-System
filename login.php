<?php
session_start();
include 'db.php'; 

if(isset($_POST['login'])){
    $user_input = mysqli_real_escape_string($conn, trim($_POST['user_input']));
    $pass = mysqli_real_escape_string($conn, trim($_POST['password']));
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "SELECT * FROM users WHERE (email='$user_input' OR phone='$user_input') 
            AND password='$pass' 
            AND LOWER(role)=LOWER('$role')";
    
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);
        
        // --- BADLAV: Doctor ko alert dikha kar rokna nahi hai ---
        // Purana block wala code hata diya gaya hai taaki doctor onboarding form tak pahunch sake.

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = strtolower($user['role']);
        $_SESSION['user_name'] = $user['name'] ?? 'User';

        if($_SESSION['role'] == 'admin') {
            echo "<script>window.location.href='admin_dashboard.php';</script>";
        } elseif($_SESSION['role'] == 'doctor'){
            // Ab pending doctor seedha dashboard pe jayega aur wahan use form dikhega
            echo "<script>window.location.href='doctor_dashboard.php';</script>";
        } else {
            echo "<script>window.location.href='patient_dashboard.php';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Invalid Details! Email/Password ya Role check karein.'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; display: flex; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); overflow: hidden; width: 850px; }
        .side-img { background: #3498db; color: white; width: 40%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; text-align: center; }
        .form-area { width: 60%; padding: 40px 50px; }
        input, select, button { width: 100%; padding: 12px; margin: 8px 0; border-radius: 10px; border: 1px solid #ddd; outline: none; box-sizing: border-box; }
        button { background: #3498db; color: white; border: none; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 15px; transition: 0.3s; }
        button:hover { background: #2980b9; }
        .reg-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .reg-link a { color: #e74c3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="side-img">
            <i class="fas fa-hospital-user" style="font-size: 80px; margin-bottom: 20px;"></i>
            <h2>CareSync Elite</h2>
        </div>
        <div class="form-area">
            <h2>Sign In</h2>
            <form action="login.php" method="POST">
                <input type="text" name="user_input" placeholder="Email or Phone" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="patient">Patient Portal</option>
                    <option value="doctor">Doctor Console</option>
                    <option value="admin">Administrator</option> 
                </select>
                <button type="submit" name="login">Login Now</button>
            </form>
            <div class="reg-link">Don't have an account? <a href="signup.php">Register Now</a></div>
        </div>
    </div>
</body>
</html>