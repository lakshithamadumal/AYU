<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$text = $data["text"];

$api_key = "sk_0da7a8bc783fc9d840741cb9da3a8039869dce52c76c7854"; // ElevenLabs API Key එක මෙතැන දමන්න
$voice_id = "Xb7hH8MSUJpSbSDYk0k2"; // ElevenLabs voice ID එක

$url = "https://api.elevenlabs.io/v1/text-to-speech/$voice_id";
$options = [
    "headers" => [
        "Content-Type" => "application/json",
        "xi-api-key" => $api_key
    ],
    "body" => json_encode([
        "text" => $text,
        "model_id" => "eleven_monolingual_v1",
        "voice_settings" => ["stability" => 0.5, "similarity_boost" => 0.8]
    ]),
    "method" => "POST"
];

$response = file_get_contents($url, false, stream_context_create(["http" => $options]));

echo $response;
?>
