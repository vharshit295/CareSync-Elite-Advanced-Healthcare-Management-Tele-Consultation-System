<?php
session_start();
include 'db.php';

// Auth Check: Agar login nahi hai toh login page par bhejo
if(!isset($_SESSION['user_id'])){ 
    header("Location: login.php"); 
    exit(); 
}

$p_id = $_SESSION['user_id'];
// Error fix: Agar 'name' session mein nahi hai toh database se lo
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Patient';

// Query: Reports, Doctor aur Patient ka data nikalna
$query = "SELECT r.*, d.name as doc_name, d.specialization, p.name as pat_name 
          FROM reports r 
          JOIN users d ON r.doctor_id = d.id 
          JOIN users p ON r.patient_id = p.id
          WHERE r.patient_id = '$p_id' ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Medical Records | CareSync</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f2f5; padding: 20px; }
        .prescription-card { 
            background: white; width: 100%; max-width: 700px; margin: 30px auto; 
            border-top: 5px solid #e74c3c; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .doc-details h2 { margin: 0; color: #2c3e50; }
        .patient-info { display: flex; justify-content: space-between; background: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 8px; }
        .rx-body { min-height: 200px; font-size: 16px; line-height: 1.6; color: #444; }
        .rx-symbol { font-size: 35px; color: #e74c3c; font-weight: bold; margin-bottom: 10px; }
        .file-link { background: #3498db; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

<h2 style="text-align:center; color:#2c3e50;">Medical History: <?php echo $user_name; ?></h2>

<?php if(mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="prescription-card">
            <div class="header">
                <div class="doc-details">
                    <h2>Dr. <?php echo $row['doc_name']; ?></h2>
                    <small><?php echo $row['specialization']; ?></small>
                </div>
                <div class="date">
                    <strong>Date:</strong> <?php echo date('d M, Y', strtotime($row['created_at'])); ?>
                </div>
            </div>

            <div class="patient-info">
                <span><strong>Patient Name:</strong> <?php echo $row['pat_name']; ?></span>
                <span><strong>ID:</strong> #PT-<?php echo $row['patient_id']; ?></span>
            </div>

            <div class="rx-symbol">Rx</div>
            <div class="rx-body">
                <?php echo nl2br(htmlspecialchars($row['prescription_text'])); ?>
            </div>

            <?php if(!empty($row['report_file'])): ?>
                <a href="uploads/<?php echo $row['report_file']; ?>" target="_blank" class="file-link">
                    <i class="fas fa-file-pdf"></i> View Attached Lab Report
                </a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center; color:gray; margin-top:50px;">No medical records found.</p>
<?php endif; ?>

</body>
</html>