<?php
header('Content-Type: application/json; charset=utf-8');

// vector_search.php එක load කරන්න
require_once 'vector_search.php';

// Vector search class එක initialize කරන්න
$vectorSearch = new VectorSearch('data/health_vectors.json');

// JSON Embeddings වල data එක ලෝඩ් කරන්න
$qaDatabase = json_decode(file_get_contents('data/health_vectors.json'), true);

// පරිශීලක query එක ගන්න
$userQuery = isset($_GET["query"]) ? $_GET["query"] : "";

$bestMatch = $vectorSearch->findBestMatch($userQuery, $qaDatabase);

if ($bestMatch) {
    // JSON Embedding එකේ ප්‍රශ්නයට උත්තරයක් ලබාදෙන්න
    echo json_encode(["response" => $bestMatch, "source" => "health_vector_match"], JSON_UNESCAPED_UNICODE);
} else {
    // JSON එකේ ප්‍රශ්නයක් නැත්නම් Gemini API එකෙන් response එක ගන්න
    $api_key = "AIzaSyALpx66FfUfRqJQUh6tiop3QmO678WHq1A"; // ඔබේ API Key එක මෙතැනට දමන්න
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $api_key;

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $userQuery]
                ]
            ]
        ]
    ];

    $payload = json_encode($data);

    // cURL request යවන්න
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        $generatedResponse = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "සමාවෙන්න, මට පිළිතුරක් ලබාදිය නොහැක.";
        echo json_encode(["response" => $generatedResponse, "source" => "gemini_api"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["response" => "AYU AI එකේ දෝෂයක් සිදුවිය.", "source" => "error"], JSON_UNESCAPED_UNICODE);
    }
}
?>
