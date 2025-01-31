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

// පළමුව `$qaDatabase` තුළින් ගැලපෙන පිළිතුරක් සොයන්න
$bestMatch = $vectorSearch->findBestMatch($userQuery, $qaDatabase);

if ($bestMatch) {
    // JSON Embedding එකේ ප්‍රශ්නයට උත්තරයක් ලබාදෙන්න
    echo json_encode(["response" => $bestMatch, "source" => "health_vector_match"], JSON_UNESCAPED_UNICODE);
} else {
    // JSON එකේ ප්‍රශ්නයක් නැත්නම් fallback response එකක්
    echo json_encode(["response" => "මේ පිළිබඳව තවදුරටත් තොරතුරු නැහැ.", "source" => "fallback"], JSON_UNESCAPED_UNICODE);
}
?>
