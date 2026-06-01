<?php 
include 'db.php'; 
// SQL Query: Harshit & Ayush, yahan hum 11 doctors fetch kar rahe hain
$doc_query = mysqli_query($conn, "SELECT name, gender, specialization, clinic_address, available_days, shift_time, phone FROM users WHERE role='doctor' LIMIT 11");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareSync | Harshit & Ayush Health</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root { --primary: #ea4335; --google-blue: #4285f4; --bg: #ffffff; --text: #202124; --gray: #f8f9fa; }
        body { font-family: 'Google Sans', Arial, sans-serif; margin: 0; background: var(--bg); overflow-x: hidden; scroll-behavior: smooth; }

        /* --- SMOOTH ENTRANCE ANIMATION --- */
        @keyframes slideInUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        nav { display: flex; justify-content: space-between; align-items: center; padding: 15px 8%; background: white; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid #e0e0e0; }
        .logo { display: flex; align-items: center; gap: 10px; font-size: 24px; font-weight: 500; color: #5f6368; text-decoration: none; }
        .logo i { color: var(--primary); font-size: 28px; }

        /* --- GMAIL STYLE HERO --- */
        .hero { 
            height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; 
            background: var(--gray); border-bottom: 1px solid #e0e0e0;
        }
        .hero-content h1 { font-size: 50px; font-weight: 400; color: var(--text); margin: 0; }
        .hero-content span { color: var(--google-blue); font-weight: 700; }

        .section-padding { padding: 80px 8%; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        
        /* --- DOCTOR CARDS WITH AVATARS --- */
        .doc-card { 
            background: white; border-radius: 12px; padding: 25px; border: 1px solid #dadce0; 
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;
        }
        .doc-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); transform: translateY(-5px); border-color: var(--google-blue); }
        
        .doc-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .doc-img { width: 65px; height: 65px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gray); }
        
        .btn-gmail { 
            background: var(--google-blue); color: white; padding: 10px 25px; border-radius: 6px; 
            text-decoration: none; font-weight: 500; display: inline-block; transition: 0.2s; border: none;
        }

        /* --- FEEDBACK SECTION (Elite Vibe) --- */
        .feedback-container { background: #202124; color: white; border-radius: 20px; padding: 50px; text-align: center; margin-top: 50px; }
        .stars { color: #fbbc05; font-size: 20px; margin-bottom: 10px; }

        /* --- FOOTER HACKER ICON --- */
        .hacker-icon { color: #34a853; animation: pulse 2s infinite; font-size: 25px; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

        footer { text-align: center; padding: 40px 0; background: #f8f9fa; border-top: 1px solid #e0e0e0; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo"><i class="fas fa-envelope-open-text"></i> CareSync</a>
        <div>
            <a href="login.php" style="text-decoration:none; color:#5f6368; margin-right:20px;">Sign in</a>
            <a href="signup.php" class="btn-gmail">Get Started</a>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content animate__animated animate__zoomIn">
            <h1>Healthcare in your <span>Inbox</span></h1>
            <p style="color:#5f6368; font-size:18px;">Fast, Secure, and Direct connection by Harshit & Ayush.</p>
        </div>
    </header>

    <section class="section-padding" id="doctors">
        <h2 style="text-align:center; font-weight:400; font-size:32px; margin-bottom:40px;">Verified Specialists</h2>
        <div class="grid">
            <?php 
            $delay = 0.1;
            while($doc = mysqli_fetch_assoc($doc_query)) {
                // MALE & FEMALE AVATARS
                $avatar = ($doc['gender'] == 'Female') 
                    ? 'https://cdn-icons-png.flaticon.com/512/3304/3304567.png' // Female Doctor
                    : 'https://cdn-icons-png.flaticon.com/512/1053/1053244.png'; // Male Doctor
            ?>
            <div class="doc-card animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay; ?>s;">
                <div class="doc-header">
                    <img src="<?php echo $avatar; ?>" class="doc-img">
                    <div>
                        <h3 style="margin:0; font-size:18px;">Dr. <?php echo $doc['name']; ?></h3>
                        <div style="color:var(--google-blue); font-size:13px; font-weight:bold;"><i class="fas fa-phone"></i> +91 <?php echo $doc['phone']; ?></div>
                    </div>
                </div>
                
                <div style="font-size:14px; color:#5f6368; line-height:1.8;">
                    <div><i class="fas fa-stethoscope" style="width:20px;"></i> <?php echo $doc['specialization']; ?></div>
                    <div><i class="fas fa-map-marker-alt" style="width:20px;"></i> <?php echo $doc['clinic_address']; ?></div>
                    <div><i class="fas fa-clock" style="width:20px;"></i> <?php echo $doc['shift_time']; ?></div>
                </div>
                
                <a href="login.php" class="btn-gmail" style="width:100%; text-align:center; box-sizing:border-box; margin-top:15px; background:transparent; color:var(--google-blue); border:1px solid #dadce0;">Reserve Session</a>
            </div>
            <?php $delay += 0.1; } ?>
        </div>
    </section>

    <section class="section-padding">
        <div class="feedback-container animate__animated animate__fadeIn">
            <div class="stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <h2 style="margin:10px 0;">"The most reliable health platform"</h2>
            <p style="opacity:0.8;">"Harshit & Ayush have created something truly helpful for the community."</p>
            <p style="font-weight:bold; margin-top:20px;">— Elite User Review</p>
        </div>
    </section>

    <footer>
        <div style="margin-bottom: 15px;">
            <i class="fas fa-user-secret hacker-icon"></i>
        </div>
        <p style="color:#5f6368; font-size:14px;">Designed with ❤️ by Harshit Verma & Ayush Tiwari</p>
        <p style="font-size:12px; color:#70757a; margin-top:10px;">&copy; 2026 CareSync | Secure Data Transmission Active</p>
    </footer>

</body>
</html>