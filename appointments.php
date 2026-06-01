<?php
session_start();
include 'db.php';

// Security check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient'){
    header("Location: login.php");
    exit();
}

if(isset($_POST['confirm_app'])){
    $pid = $_SESSION['user_id'];
    $doc = $_POST['doctor'];
    $date = $_POST['date'];
    $consultation = $_POST['consultation_type'];

    // 🔥 TOKEN LOGIC
    $token_q = "SELECT MAX(token_number) as max_token 
                FROM appointments 
                WHERE doctor_id='$doc' AND app_date='$date'";
                
    $token_res = mysqli_query($conn, $token_q);
    $token_row = mysqli_fetch_assoc($token_res);

    $new_token = ($token_row['max_token']) ? $token_row['max_token'] + 1 : 1;

    // 🔥 WAITING COUNT
    $waiting_q = "SELECT COUNT(*) as total 
                  FROM appointments 
                  WHERE doctor_id='$doc' 
                  AND app_date='$date' 
                  AND token_number < '$new_token' 
                  AND status!='completed'";
                  
    $waiting_res = mysqli_query($conn, $waiting_q);
    $waiting_data = mysqli_fetch_assoc($waiting_res);

    $people_before = $waiting_data['total'];
    $waiting_time = $people_before * 10; // 10 min per patient

    // 🔥 INSERT QUERY
    $q = "INSERT INTO appointments 
          (patient_id, doctor_id, app_date, token_number, consultation_type, status) 
          VALUES ('$pid', '$doc', '$date', '$new_token', '$consultation', 'pending')";
    
    if(mysqli_query($conn, $q)){
        echo "<script>
                alert('Appointment Booked!\\nToken: #$new_token\\nWaiting Time: approx $waiting_time minutes');
                window.location='patient_dashboard.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Consultation | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; }
        
        .box { 
            background: white; padding: 40px; border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 420px; 
            transition: all 0.4s ease;
            border-top: 8px solid #e74c3c;
        }
        .box:hover { transform: scale(1.05); }

        h2 { color: #2c3e50; margin-bottom: 20px; text-align: center; }
        input, select, button { width: 100%; padding: 14px; margin: 12px 0; border-radius: 10px; border: 1px solid #ddd; }
        
        button { 
            background: #e74c3c; color: white; border: none; font-weight: bold; 
            cursor: pointer; font-size: 16px; 
        }
        button:hover { background: #c0392b; }
        
        .back-link { display: block; text-align: center; margin-top: 15px; color: #7f8c8d; text-decoration: none; }
        .back-link:hover { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="box">
        <h2><i class="fas fa-calendar-alt" style="color:#e74c3c;"></i> Book Appointment</h2>
        
        <form method="POST">
            
            <label>Select Doctor</label>
            <select name="doctor" required>
                <option value="">Choose your Doctor...</option>
                <?php
                $docs = mysqli_query($conn, "SELECT id, email FROM users WHERE role='doctor'");
                while($d = mysqli_fetch_assoc($docs)){
                    echo "<option value='".$d['id']."'>Dr. (".$d['email'].")</option>";
                }
                ?>
            </select>

            <label>Preferred Date</label>
            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">

            <!-- 🔥 NEW FEATURE -->
            <label>Consultation Type</label>
            <select name="consultation_type" required>
                <option value="offline">Offline Visit</option>
                <option value="online">Online Consultation</option>
            </select>

            <button name="confirm_app">Confirm & Book</button>
        </form>

        <a href="patient_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>