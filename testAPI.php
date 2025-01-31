<?php
$apiKey = "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";
$apiUrl = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-3.5-turbo",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful assistant."],
        ["role" => "user", "content" => "Hello!"]
    ]
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer " . $apiKey . "\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
        "ignore_errors" => true
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);

echo $response;
?>
