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
