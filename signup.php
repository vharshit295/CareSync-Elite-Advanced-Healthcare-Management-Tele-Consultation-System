<?php
include 'db.php';
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']); 
    
    // Age Validation
    $age = (int)$_POST['age'];
    if ($age <= 0 || $age > 120) {
        echo "<script>alert('Error: Age 1 se 120 ke beech honi chahiye!'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        echo "<script>alert('Error: Name mein sirf letters allowed hain!'); window.history.back();</script>";
        exit();
    }

    $email = !empty($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : "";
    $phone = mysqli_real_escape_string($conn, $_POST['phone']); 
    $pass = $_POST['password'];
    $role = $_POST['role'];
    $gender = $_POST['gender'];

    if(empty($role)){
        echo "<script>alert('Error: Please select a Role!'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO users (name, email, phone, password, role, age, gender) VALUES ('$name', '$email', '$phone', '$pass', '$role', '$age', '$gender')";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Account Created Successfully!'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI'; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin:0; }
        .container { background: white; display: flex; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); overflow: hidden; width: 850px; }
        .side-img { background: #2c3e50; color: white; width: 35%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center; }
        .form-area { width: 65%; padding: 30px 40px; }
        .req { color: #e74c3c; font-weight: bold; }
        label { font-size: 14px; color: #7f8c8d; font-weight: bold; }
        
        input, select, button { 
            width: 100%; 
            padding: 12px; 
            margin: 6px 0; 
            border-radius: 8px; 
            border: 1px solid #ddd; 
            outline: none; 
            box-sizing: border-box; 
        }

        /* Age Box and Gender Spacing */
        .input-row { display: flex; gap: 15px; align-items: flex-end; }
        .age-box { flex: 1.5; } 
        .gender-box { flex: 2.5; }
        
        button { background: #e74c3c; color: white; border: none; cursor: pointer; font-weight: bold; margin-top: 15px; font-size: 16px; transition: 0.3s; }
        button:hover { background: #c0392b; }
        .login-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .login-link a { color: #3498db; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="side-img">
            <i class="fas fa-hospital-user" style="font-size: 70px; margin-bottom: 15px;"></i>
            <h2>CareSync</h2>
            <p>Join our secure healthcare network today.</p>
        </div>
        <div class="form-area">
            <h2 style="color: #2c3e50;">Create Account</h2>
            <p style="font-size: 13px; margin-bottom: 15px;">Fields with <span class="req">*</span> are mandatory.</p>
            
            <form method="POST">
                <label>Full Name <span class="req">*</span></label>
                <input type="text" name="name" placeholder="Full Name" pattern="[A-Za-z\s]+" required>
                
                <div class="input-row">
                    <div class="age-box">
                        <label>Age <span class="req">*</span></label>
                        <input type="number" name="age" min="1" max="120" placeholder="Age" required>
                    </div>
                    <div class="gender-box">
                        <label>Gender <span class="req">*</span></label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Transgender">Transgender</option>
                        </select>
                    </div>
                </div>

                <label>Mobile Number <span class="req">*</span></label>
                <input type="text" name="phone" placeholder="+91 9876543210" required>

                <label>Email Address (Optional)</label>
                <input type="email" name="email" placeholder="Email Address (Optional)">
                
                <label>Password <span class="req">*</span></label>
                <input type="password" name="password" placeholder="Password" required>

                <label>Register As <span class="req">*</span></label>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                </select>

                <button name="signup">Register Now</button>
            </form>

            <div class="login-link">
                Already registered? <a href="login.php">Login Here</a>
            </div>
        </div>
    </div>
</body>
</html>