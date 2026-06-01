<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- Days Formatting Function ---
function formatDaysRange($selected_days) {
    if (empty($selected_days)) return "";
    $all_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    
    if (count($selected_days) == 7) return "Daily";

    // Sequence check logic
    $indices = [];
    foreach ($selected_days as $day) {
        $index = array_search($day, $all_days);
        if ($index !== false) $indices[] = $index;
    }
    sort($indices);

    $is_sequence = true;
    for ($i = 0; $i < count($indices) - 1; $i++) {
        if ($indices[$i+1] - $indices[$i] !== 1) {
            $is_sequence = false;
            break;
        }
    }

    if ($is_sequence && count($indices) > 1) {
        return $all_days[min($indices)] . " - " . $all_days[max($indices)];
    } else {
        return implode(", ", $selected_days);
    }
}

if(isset($_POST['save_profile'])){
    $license = mysqli_real_escape_string($conn, $_POST['license_no']);
    $spec = mysqli_real_escape_string($conn, $_POST['specialization']);
    $address = mysqli_real_escape_string($conn, $_POST['clinic_address']);
    
    // 1. Time ko AM/PM format mein convert karke shift_time banana
    $s_time = date("h:i A", strtotime($_POST['start_time']));
    $e_time = date("h:i A", strtotime($_POST['end_time']));
    $shift_time = $s_time . " - " . $e_time;

    // 2. Days ko range format mein convert karna
    $selected_days = isset($_POST['days']) ? $_POST['days'] : [];
    $formatted_days = formatDaysRange($selected_days);

    // 3. Database Update
    $sql = "UPDATE users SET 
            specialization='$spec', 
            license_no='$license', 
            clinic_address='$address', 
            available_days='$formatted_days', 
            shift_time='$shift_time', 
            profile_complete=1 
            WHERE id='$user_id'";

    if(mysqli_query($conn, $sql)){
        header("Location: doctor_dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>CareSync | Complete Doctor Profile</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 20px 0; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 450px; }
        h2 { color: #2c3e50; text-align: center; margin-top: 0; }
        input, select, textarea { width: 100%; padding: 10px; margin: 10px 0; border-radius: 6px; border: 1px solid #ddd; box-sizing: border-box; }
        .days-section { margin: 15px 0; font-size: 14px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
        .time-section { display: flex; gap: 10px; align-items: center; }
        button { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 20px; }
        button:hover { background: #2980b9; }
        label { font-weight: bold; font-size: 14px; color: #34495e; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🏥 Doctor Onboarding</h2>
        <p style="text-align: center; color: #7f8c8d;">Please provide your professional details.</p>
        
        <form method="POST">
            <label>License Details</label>
            <input type="text" name="license_no" placeholder="Medical License Number" required>

            <label>Specialization</label>
            <select name="specialization" required>
                <option value="">-- Select Specialty --</option>
                <option value="Cardiologist">Cardiologist</option>
                <option value="Neurologist">Neurologist</option>
                <option value="Pediatrician">Pediatrician</option>
                <option value="Orthopedic">Orthopedic</option>
                <option value="General Surgeon">General Surgeon</option>
            </select>

            <label>Clinic Address</label>
            <textarea name="clinic_address" placeholder="Full Clinic Address..." rows="3" required></textarea>

            <label>Available Days</label>
            <div class="days-section">
                <?php 
                $days_list = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                foreach($days_list as $day) {
                    echo "<label><input type='checkbox' name='days[]' value='$day'> $day</label>";
                }
                ?>
            </div>

            <label>Consultation Hours</label>
            <div class="time-section">
                <input type="time" name="start_time" required>
                <span>to</span>
                <input type="time" name="end_time" required>
            </div>

            <button type="submit" name="save_profile">Complete & Join CareSync</button>
        </form>
    </div>
</body>
</html>