<?php
session_start();
include 'db.php';

if(isset($_POST['update_all']) || isset($_POST['update_onboarding'])){
    $doc_id = $_SESSION['user_id'];
    
    // Form se data lena aur safe banana
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['clinic_address']);
    $time = mysqli_real_escape_string($conn, $_POST['shift_time']);
    $pass = $_POST['password'] ?? '';

    // --- SMART DAY LOGIC (Daily / Range / List) ---
    $days_array = $_POST['days'] ?? [];
    $all_weekdays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    $days_count = count($days_array);
    $days_final_val = "";

    if ($days_count == 7) {
        $days_final_val = "Daily";
    } elseif ($days_count > 1) {
        // Days ko week order ke hisaab se sort karna
        usort($days_array, function($a, $b) use ($all_weekdays) {
            return array_search($a, $all_weekdays) - array_search($b, $all_weekdays);
        });

        $first_idx = array_search($days_array[0], $all_weekdays);
        $is_continuous = true;
        for ($i = 1; $i < $days_count; $i++) {
            $current_idx = array_search($days_array[$i], $all_weekdays);
            if ($current_idx !== $first_idx + $i) {
                $is_continuous = false;
                break;
            }
        }

        if ($is_continuous) {
            $days_final_val = $days_array[0] . " - " . end($days_array);
        } else {
            $days_final_val = implode(", ", $days_array);
        }
    } else {
        $days_final_val = implode(", ", $days_array);
    }

    // --- BASE UPDATE QUERY ---
    $sql = "UPDATE users SET 
            name='$name', 
            clinic_address='$address', 
            shift_time='$time', 
            available_days='$days_final_val'";

    // Agar Password change karna hai
    if(!empty($pass)) {
        $sql .= ", password='$pass'";
    }

    // Photo Upload Logic
    if(isset($_FILES['profile_pic']) && !empty($_FILES['profile_pic']['name'])){
        $img_name = time() . "_" . $_FILES['profile_pic']['name'];
        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/" . $img_name)){
            $sql .= ", profile_pic='$img_name'";
        }
    }

    $sql .= " WHERE id='$doc_id'";

    // Final Execution
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Sab kuch save ho gaya!'); window.location='doctor_dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>