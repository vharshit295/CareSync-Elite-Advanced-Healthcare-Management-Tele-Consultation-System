<?php
session_start();
include 'db.php';

// Timezone fix taaki chat ka time sahi rahe
date_default_timezone_set('Asia/Kolkata'); 

if(!isset($_SESSION['user_id'])){ 
    header("Location: login.php"); 
    exit(); 
}

$appt_id = $_GET['id'];
$room = "CareSync_Room_" . $appt_id;
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? $_SESSION['user_name'] ?? 'User';
$role = $_SESSION['role'];

// --- Identity Hiding Logic Start ---
$p_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT u.name, a.hide_identity FROM users u JOIN appointments a ON u.id = a.patient_id WHERE a.id = '$appt_id'"));
$caller_name = ($p_info['hide_identity'] == 1) ? "Anonymous" : $p_info['name'];
// --- Identity Hiding Logic End ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareSync | Consultation Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src='https://meet.jit.si/external_api.js'></script>
    <style>
        body { margin: 0; display: flex; height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #000; overflow: hidden; }
        #video-container { flex: 3; background: #1a1a1a; }
        
        /* CHAT SIDEBAR UI */
        #chat-container { flex: 1; background: #fff; display: flex; flex-direction: column; border-left: 2px solid #ddd; min-width: 300px; }
        .chat-header { padding: 15px; background: #2c3e50; color: white; font-weight: bold; display: flex; align-items: center; gap: 10px; }
        
        #chat-box { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: #f9f9f9; }
        
        /* MESSAGE BUBBLES */
        .msg { padding: 10px 14px; border-radius: 15px; max-width: 85%; font-size: 14px; line-height: 1.4; position: relative; word-wrap: break-word; }
        .msg.sent { align-self: flex-end; background: #e74c3c; color: white; border-bottom-right-radius: 2px; }
        .msg.received { align-self: flex-start; background: #ecf0f1; color: #333; border-bottom-left-radius: 2px; }
        
        /* INPUT AREA */
        .chat-input { padding: 15px; border-top: 1px solid #ddd; display: flex; gap: 10px; background: white; }
        .chat-input input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 25px; outline: none; transition: 0.3s; }
        .chat-input input:focus { border-color: #e74c3c; }
        .chat-input button { background: #e74c3c; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; }
        .chat-input button:hover { background: #c0392b; transform: scale(1.05); }

        /* Banner Style */
        .patient-banner { background: #fdf2f2; padding: 10px; text-align: center; border-bottom: 1px solid #eee; color: #c0392b; font-size: 14px; }
    </style>
</head>
<body>

<div id="video-container"></div>

<div id="chat-container">
    <div class="chat-header">
        <i class="fas fa-comments"></i> 
        <span>Patient-Doctor Chat</span>
    </div>

    <div class="patient-banner">
        Consulting with: <strong><?php echo $caller_name; ?></strong>
    </div>
    
    <div id="chat-box"></div>

    <div class="chat-input">
        <input type="text" id="msgText" placeholder="Type your message..." onkeypress="if(event.key === 'Enter') sendMessage()">
        <button onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
    // 1. VIDEO CALL LOGIC (Jitsi)
    const domain = 'meet.jit.si';
    const options = {
        roomName: '<?php echo $room; ?>',
        width: '100%',
        height: '100%',
        parentNode: document.querySelector('#video-container'),
        userInfo: { displayName: '<?php echo $user_name; ?>' },
        configOverwrite: { startWithAudioMuted: true },
        interfaceConfigOverwrite: { SHOW_JITSI_WATERMARK: false }
    };
    const api = new JitsiMeetExternalAPI(domain, options);

    // 2. CHAT LOGIC (AJAX)
    
    function fetchMessages() {
        fetch(`get_messages.php?appt_id=<?php echo $appt_id; ?>`)
        .then(res => res.text())
        .then(data => {
            const chatBox = document.getElementById('chat-box');
            const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 1;
            
            chatBox.innerHTML = data;
            
            if (isScrolledToBottom) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    }

    function sendMessage() {
        let msgInput = document.getElementById('msgText');
        let msg = msgInput.value.trim();
        
        if(msg === "") return;
        
        fetch('send_message.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `appt_id=<?php echo $appt_id; ?>&msg=${encodeURIComponent(msg)}`
        }).then(() => {
            msgInput.value = ""; 
            fetchMessages(); 
        });
    }

    setInterval(fetchMessages, 2000);
    window.onload = fetchMessages;
</script>

</body>
</html>