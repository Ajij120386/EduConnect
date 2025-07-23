<?php
session_start();
if (!isset($_SESSION['chat_history'])) $_SESSION['chat_history'] = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prompt'])) {
    $userPrompt = trim($_POST['prompt'] ?? '');
    if ($userPrompt !== '') {
        $_SESSION['chat_history'][] = ['role' => 'user', 'content' => $userPrompt];

        // $secretKey = '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';
        $endpoint = 'https://api.groq.com/openai/v1/chat/completions';

        $data = [
            'messages' => [
                ['role' => 'system', 'content' => "You are a friendly computer science tutor for CSE students."],
                ['role' => 'user', 'content' => $userPrompt]
            ],
            'model' => 'llama3-8b-8192'
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $secretKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);
        $aiText = $responseData['choices'][0]['message']['content'] ?? "No response from AI.";
        $aiText = strip_tags($aiText, '<pre><code>');

        $_SESSION['chat_history'][] = ['role' => 'ai', 'content' => $aiText];
        echo json_encode(['status' => 'success']);
        exit();
    }
}

if (isset($_POST['reset'])) {
    $_SESSION['chat_history'] = [];
    echo json_encode(['status' => 'reset']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AI CSE Tutor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css"/>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0f1117; color: #eee; margin: 0; padding: 0; }
        .container {
            
            border: 2px solid #00bcd4; /* bright cyan border */
    border-radius: 12px;
    padding: 20px;
    background-color: #1e1e2f;
    box-shadow: 0 0 12px rgba(0, 188, 212, 0.6); /* glowing effect */
    
    max-width: 800px; margin: 30px auto; background: #1c1f26; padding: 20px; border-radius: 8px; box-shadow: 0 0 20px rgba(0,0,0,0.4); }
        .header { text-align: center; font-size: 28px; color: #00d8ff; margin-bottom: 20px; }
        .tags { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 20px; }
        .tags button { background: #282c34; color: #aaa; border: 1px solid #444; padding: 6px 12px; border-radius: 20px; cursor: pointer; }
        .tags button:hover { color: #fff; border-color: #00d8ff; }
        .chat-box { max-height: 400px; overflow-y: auto; padding: 10px; margin-bottom: 20px; }
        .message { margin-bottom: 15px; }
        .user { background: #0066cc; color: #fff; text-align: right; padding: 12px; border-radius: 10px; }
        .ai { background: #2a2e39; color: #ccc; padding: 12px; border-radius: 10px; white-space: pre-wrap; }
        form { display: flex; gap: 10px; }
       input[type="text"] {
    flex: 1;
    padding: 14px 16px;
    border-radius: 8px;
     border: 1px solid #5a5f67;  /* formal muted-gray border */
    background-color: #1e222a;  /* slightly lighter than background */
    color: #ffffff;             /* keep text white */
    font-size: 15px;
    outline: none;
    transition: 0.3s ease;
}

input[type="text"]::placeholder {
    color: #aaaeb3;  /* soft light-gray placeholder */
}

input[type="text"]:focus {
    border-color: #58a6ff;
    box-shadow: 0 0 6px rgba(88, 166, 255, 0.4);
    background-color: #252b33;  /* subtly lighter on focus */
}

        button[type="submit"], .reset-btn { background: #00d8ff; color: #000; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; }
        .reset-btn { margin-left: 10px; }
        pre, code { font-family: 'Fira Code', monospace; font-size: 14px; }

        .top-bar {
            background: linear-gradient(90deg, #4f46e5, #3b82f6);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-back {
            background-color: white;
            color: #4f46e5;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            transition: 0.3s ease;
        }
        .btn-back:hover {
            background-color: #e0e7ff;
        }

        .dashboard-wrapper {
            max-width: 1200px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-between align-items-center top-bar">
      
        <a href="index.php" class="btn btn-back px-3">← Back to Main Site</a>
        
    
</div>
<div class="container">
    <div class="header">📚 Academic AI Assistant</div>

    
    <div class="chat-box" id="chat">
        <?php foreach ($_SESSION['chat_history'] as $entry): ?>
            <div class="message <?= $entry['role'] === 'user' ? 'user' : 'ai' ?>">
                <?= $entry['role'] === 'user' ? htmlspecialchars($entry['content']) : markdownToHtml($entry['content']) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <form id="chatForm">
        <input type="text" name="prompt" id="promptInput" placeholder="Ask Anything..." autocomplete="off" required />
        <button type="submit">Send</button>
        <button type="button" class="reset-btn" onclick="resetChat()">Reset Chat</button>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-cpp.min.js"></script>
<script>
    function setPrompt(text) {
        document.getElementById('promptInput').value = text;
        document.getElementById('promptInput').focus();
    }

    document.getElementById('chatForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const promptInput = document.getElementById('promptInput');
        const prompt = promptInput.value;
        if (!prompt.trim()) return;

        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'prompt=' + encodeURIComponent(prompt)
        })
        .then(res => res.json())
        .then(data => {
            promptInput.value = '';
            setTimeout(() => {
                location.reload();
                const chatBox = document.getElementById('chat');
                chatBox.scrollTop = chatBox.scrollHeight;
            }, 100);
        });
    });

    function resetChat() {
        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'reset=1'
        }).then(res => res.json()).then(data => location.reload());
    }

    // Scroll to bottom on page load
    window.onload = function () {
        const chatBox = document.getElementById('chat');
        chatBox.scrollTop = chatBox.scrollHeight;
    };
</script>
</body>
</html>

<?php
function markdownToHtml($text) {
    $text = str_replace("<br />", "", $text);
    return preg_replace_callback('/```(.*?)```/s', function ($matches) {
        $code = trim($matches[1]);
        return "<pre><code class=\"language-cpp\">" . htmlspecialchars($code) . "</code></pre>";
    }, $text);
}
?> 