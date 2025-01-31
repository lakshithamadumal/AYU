<?php
header('Content-Type: application/json; charset=utf-8');

// vector_search.php එක load කරන්න
require_once 'vector_search.php';

// Vector search class එක initialize කරන්න
$vectorSearch = new VectorSearch('data/health_vectors.json');

// $qaDatabase එක (තව ප්‍රශ්න සහ උත්තර ඇතුළත් කරන්න පුළුවන්)
$qaDatabase = [
    "මානසික ආතතියට ප්‍රතිකාර" => "මානසික ආතතිය සඳහා සෙරපි, යෝගා, සහ හොඳ නිදියුම් ප්‍රතිකාර වේ.",
    //"ආහාර පාලනයේ වාසි" => "ආහාර පාලනය හොඳ සෞඛ්‍යයක් සඳහා මහඟු වේ.",
    //"ජලය පානය කිරීමේ වාසි" => "ජලය පානය කිරීම ශරීරයේ තෙතමනය පවත්වා ගැනීමට සහය වේ."
];

// පරිශීලකයාගේ query එක ගන්න
$userQuery = isset($_GET["query"]) ? $_GET["query"] : "";

// පළමුව `$qaDatabase` තුළින් ගැලපෙන පිළිතුරක් සොයන්න
$bestMatch = $vectorSearch->findBestMatch($userQuery, $qaDatabase);

if ($bestMatch) {
    echo json_encode(["response" => $bestMatch, "source" => "health_vector_match"], JSON_UNESCAPED_UNICODE);
} else {
    // $qaDatabase තුළ නැති ප්‍රශ්නයක් ඇහුවොත්, vector data එකෙන් උත්තරයක් ලබාදෙන්න
    $vectorMatch = $vectorSearch->findVectorMatch($userQuery);

    if ($vectorMatch) {
        echo json_encode(["response" => $vectorMatch, "source" => "health_vector_fallback"], JSON_UNESCAPED_UNICODE);
    } else {
        // තවමත් ගැලපෙන එකක් නැත්නම් fallback response එකක්
        echo json_encode(["response" => "මට මේ පිළිබඳව තවදුරටත් තොරතුරු නැහැ.", "source" => "fallback"], JSON_UNESCAPED_UNICODE);
    }
}
?>
