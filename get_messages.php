<?php
session_start();
include 'db.php';

$appt_id = $_GET['appt_id'];
$current_user = $_SESSION['user_id'];

// Pehle appointment aur patient ki identity preference fetch kar lete hain
$p_info_query = "SELECT a.patient_id, a.hide_identity, u.name 
                 FROM appointments a 
                 JOIN users u ON a.patient_id = u.id 
                 WHERE a.id = '$appt_id'";
$p_info_res = mysqli_fetch_assoc(mysqli_query($conn, $p_info_query));

$patient_id = $p_info_res['patient_id'];
$is_hidden = $p_info_res['hide_identity'];
$patient_name = $p_info_res['name'];

// Messages fetch karein
$query = "SELECT * FROM messages WHERE appointment_id = '$appt_id' ORDER BY timestamp ASC";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)) {
    // Check karein message kisne bheja hai
    $sender_id = $row['sender_id'];
    $class = ($sender_id == $current_user) ? 'sent' : 'received';
    
    // Identity Hiding Logic:
    // Agar sender patient hai aur usne identity hide ki hai, 
    // aur dekhne wala (current_user) khud wo patient nahi hai (yani doctor dekh raha hai),
    // toh hum "Anonymous" label use kar sakte hain agar aap naam dikhana chahte ho.
    
    // Note: Aapne pehle sirf message bubble dikhaya tha, 
    // main wahi format maintain kar raha hoon taaki design na bigde.
    echo "<div class='msg $class'>" . htmlspecialchars($row['message']) . "</div>";
}
?>