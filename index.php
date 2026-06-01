<?php 
include 'db.php'; 
$doc_query = mysqli_query($conn, "SELECT name, gender, specialization, clinic_address, available_days, shift_time FROM users WHERE role='doctor'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareSync Elite | Premium Healthcare</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        :root { --primary: #2563eb; --secondary: #0f172a; --accent: #38bdf8; --bg: #f8fafc; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background: var(--bg); overflow-x: hidden; scroll-behavior: smooth; }

        /* Animation for Background Zoom */
        @keyframes customZoom { from { transform: scale(1.1); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        
        nav { display: flex; justify-content: space-between; align-items: center; padding: 18px 8%; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .logo { display: flex; align-items: center; gap: 12px; font-size: 28px; font-weight: 800; color: var(--primary); text-decoration: none; }
        .logo i { background: linear-gradient(45deg, var(--primary), var(--accent)); color: white; padding: 10px; border-radius: 14px; box-shadow: 0 5px 15px rgba(37,99,235,0.3); }

        /* --- NEW ELITE HERO SECTION --- */
        .hero { 
            height: 85vh; display: flex; align-items: center; justify-content: center; text-align: center; 
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=1350&q=80'); 
            background-size: cover; background-position: center; color: white; position: relative;
        }
        .hero-content { animation: customZoom 1.2s cubic-bezier(0.2, 0, 0.2, 1); max-width: 900px; padding: 0 20px; }
        .hero-badge { background: rgba(56, 189, 248, 0.15); color: var(--accent); padding: 8px 20px; border-radius: 50px; border: 1px solid rgba(56, 189, 248, 0.3); font-size: 14px; font-weight: 600; letter-spacing: 1px; margin-bottom: 25px; display: inline-block; }

        .section-padding { padding: 100px 8%; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
        
        /* Feature Cards */
        .feature-card { 
            background: white; padding: 45px; border-radius: 30px; text-align: center; 
            transition: all 0.5s ease; border: 1px solid #f1f5f9;
        }
        .feature-card:hover { transform: translateY(-15px); box-shadow: 0 25px 50px rgba(0,0,0,0.05); border-color: var(--accent); }
        .feature-card i { font-size: 45px; background: linear-gradient(var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 25px; }

        /* Doctor Cards */
        .doc-card { background: white; border-radius: 25px; padding: 30px; border: 1px solid #f1f5f9; transition: 0.4s; }
        .doc-card:hover { border-color: var(--primary); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
        .doc-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .doc-img { width: 75px; height: 75px; border-radius: 20px; object-fit: cover; box-shadow: 0 8px 15px rgba(0,0,0,0.1); }
        .doc-detail { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #64748b; margin-bottom: 12px; }
        .doc-detail i { color: var(--primary); font-size: 16px; }

        .btn-primary { background: linear-gradient(45deg, var(--primary), var(--accent)); color: white; padding: 15px 35px; border-radius: 15px; font-weight: 700; border: none; cursor: pointer; transition: 0.3s; display: inline-block; text-decoration: none; box-shadow: 0 10px 20px rgba(37,99,235,0.2); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(37,99,235,0.4); }

        /* Footer Hacker Style */
        .hacker-icon { color: #22c55e; filter: drop-shadow(0 0 5px #22c55e); animation: blink 1.5s infinite; }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        
        footer { text-align: center; padding: 60px 0; background: #0f172a; color: white; }
    </style>
</head>
<body>

    <nav>
        <a href="index.php" class="logo"><i class="fas fa-shield-virus"></i> CareSync</a>
        <div>
            <a href="login.php" style="text-decoration:none; color:var(--secondary); font-weight:700; margin-right:30px;">Member Login</a>
            <a href="signup.php" class="btn-primary">Registration</a>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <div class="hero-badge animate__animated animate__fadeInDown">PROUDLY PRESENTED BY HARSHIT & AYUSH</div>
            <h1 style="font-size: clamp(40px, 6vw, 70px); margin: 0; font-weight: 900; line-height: 1.1;">
                Premium Healthcare <br><span style="color:var(--accent)">Simplified.</span>
            </h1>
            <p style="font-size: clamp(16px, 2vw, 20px); margin: 30px auto; opacity: 0.8; line-height: 1.6; max-width: 750px;">
                Experience the intersection of elite technology and human compassion. A bespoke digital health ecosystem designed for a smarter tomorrow.
            </p>
            <div class="animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                <a href="#doctors" class="btn-primary">Explore Specialists <i class="fas fa-chevron-right" style="margin-left:10px; font-size:12px;"></i></a>
            </div>
        </div>
    </header>

    <section class="section-padding">
        <h2 style="text-align:center; font-size:38px; font-weight:800; margin-bottom:60px;">The <span style="color:var(--primary)">Elite</span> Advantage</h2>
        <div class="grid">
            <div class="feature-card animate__animated animate__fadeInUp">
                <i class="fas fa-fingerprint"></i>
                <h3>Privacy-First Care</h3>
                <p>Advanced identity masking ensures your consultations remain 100% confidential and secure.</p>
            </div>
            <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <i class="fas fa-bolt-lightning"></i>
                <h3>Instant Resonance</h3>
                <p>Proprietary algorithms by Harshit & Ayush connect you to doctors in sub-seconds.</p>
            </div>
            <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <i class="fas fa-vault"></i>
                <h3>Data Sanctity</h3>
                <p>Your medical history is encrypted within our high-security digital vault, accessible only to you.</p>
            </div>
        </div>
    </section>

    

    <section class="section-padding" id="doctors" style="background: white;">
        <h2 style="text-align:center; font-size:38px; font-weight:800; margin-bottom:60px;">World-Class <span style="color:var(--primary)">Specialists</span></h2>
        <div class="grid">
            <?php 
            $delay = 0.1;
            while($doc = mysqli_fetch_assoc($doc_query)) {
                $avatar = ($doc['gender'] == 'Female') ? 'https://cdn-icons-png.flaticon.com/512/3304/3304567.png' : 'https://cdn-icons-png.flaticon.com/512/1053/1053244.png';
            ?>
            <div class="doc-card animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay; ?>s;">
                <div class="doc-header">
                    <img src="<?php echo $avatar; ?>" class="doc-img">
                    <div>
                        <h3 style="margin:0; font-size:20px;">Dr. <?php echo $doc['name']; ?></h3>
                        <div style="color:#facc15; font-size:12px;"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i> <span style="color:#64748b">(Elite Member)</span></div>
                    </div>
                </div>
                
                <div class="doc-detail"><i class="fas fa-award"></i> <b>Expertise:</b> <?php echo $doc['specialization']; ?></div>
                <div class="doc-detail"><i class="fas fa-hospital-user"></i> <b>Facility:</b> <?php echo $doc['clinic_address']; ?></div>
                <div class="doc-detail"><i class="fas fa-calendar-check"></i> <b>Schedule:</b> <?php echo $doc['available_days']; ?></div>
                <div class="doc-detail" style="color:var(--primary); font-weight:700;"><i class="fas fa-clock"></i> <?php echo $doc['shift_time']; ?></div>
                
                <a href="login.php" class="btn-primary" style="width:100%; text-align:center; box-sizing:border-box; margin-top:20px; padding:12px;">Reserve Session</a>
            </div>
            <?php $delay += 0.15; } ?>
        </div>
    </section>

    <footer>
        <div style="margin-bottom: 20px;">
            <i class="fas fa-user-secret hacker-icon" style="font-size: 30px;"></i>
        </div>
        <p style="font-size: 14px; opacity: 0.7; margin-bottom: 10px;">Architected with Precision by</p>
        <h3 style="color:var(--accent); margin:0; font-size:26px; font-weight: 800; letter-spacing: 1px;">Harshit Verma & Ayush Tiwari</h3>
        <p style="font-size:12px; opacity:0.4; margin-top: 30px;">&copy; 2026 CareSync Elite Ecosystem | All Security Protocols Active</p>
    </footer>

</body>
</html>