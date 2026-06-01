<!DOCTYPE html>
<html>
<head>
    <title>Upload Report | CareSync</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .upload-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        h2 { color: #1a237e; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; }
        .btn-submit { background: #00c853; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #00a846; }
        .back-link { display: block; margin-top: 15px; color: #1a237e; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="upload-card">
        <h2><i class="fas fa-cloud-upload-alt"></i> Upload Report</h2>
        <form action="save_report.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="patient_id" placeholder="Patient ID (e.g. 8829)" required>
            <input type="text" name="description" placeholder="Report Name (e.g. Blood Test)" required>
            <input type="file" name="report_file" required>
            <button type="submit" class="btn-submit">Upload to Server</button>
        </form>
        <a href="doctor_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>