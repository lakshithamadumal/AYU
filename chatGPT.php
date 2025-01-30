<?php
if (isset($_GET['prompt']) && $_GET['prompt'] != '' && isset($_GET['role']) && $_GET['role'] != '') {
    // Your OpenAI API key
    $apiKey = "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";

    // API endpoint
    $url = "https://api.openai.com/v1/chat/completions";

    // Request payload
    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            [
                "role" => "system",
                "content" => $_GET['role']
            ],
            [
                "role" => "user",
                "content" => $_GET['prompt']
            ]
        ]
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo json_encode(["error" => curl_error($ch)], JSON_PRETTY_PRINT);
    } else {
        // Decode and display the response in JSON format
        header('Content-Type: application/json');
        echo $response;
    }

    // Close the cURL session
    curl_close($ch);
} else {
    return ['message' => false];
}
