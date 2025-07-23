<?php
session_start();
include 'conn.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['reply' => '❌ You must be logged in.']);
    exit;
}

$user_id = $_SESSION['id'];
$data = json_decode(file_get_contents('php://input'), true);
$userPrompt = trim($data['message'] ?? '');

if (empty($userPrompt)) {
    echo json_encode(['reply' => '❌ Please type a question.']);
    exit;
}

// ✅ Check allowed topics
$allowedTopics = ['stack', 'queue', 'array', 'graph', 'tree', 'sorting', 'recursion', 'heap', 'pointer', 'linked list', 'algorithm', 'data structure', 'oop', 'inheritance', 'constructor'];
$valid = false;
foreach ($allowedTopics as $topic) {
    if (stripos($userPrompt, $topic) !== false) {
        $valid = true;
        break;
    }
}
if (!$valid) {
    echo json_encode(['reply' => '⚠️ Please ask syllabus-related questions only.']);
    exit;
}

// ✅ Hugging Face API - flan-t5-large
$apiKey = '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';
$endpoint = 'https://api-inference.huggingface.co/models/google/flan-t5-large';

$prompt = "Explain in simple terms: " . $userPrompt;

$payload = json_encode([
    "inputs" => $prompt
]);

$headers = [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
file_put_contents('huggingface_debug.json', $response);

if (curl_errno($ch)) {
    echo json_encode(['reply' => '❌ CURL Error: ' . curl_error($ch)]);
    exit;
}
curl_close($ch);

// ✅ Decode response
$responseData = json_decode(trim($response), true);

// Check for error
if (isset($responseData['error'])) {
    echo json_encode(['reply' => '❌ Hugging Face Error: ' . $responseData['error']]);
    exit;
}

// Extract answer
$reply = $responseData[0]['generated_text'] ?? '⚠️ AI could not generate a valid answer.';

// Save to DB
$stmt = $conn->prepare("INSERT INTO ai_questions (user_id, question, answer) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $userPrompt, $reply);
$stmt->execute();
$stmt->close();

echo json_encode(['reply' => $reply]);
exit;
