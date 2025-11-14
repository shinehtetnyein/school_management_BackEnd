<?php

$baseUrl = 'http://127.0.0.1:8000/api/v1/teachers';

$response = @file_get_contents($baseUrl);

if ($response === false) {
    echo "Failed to fetch from API\n";
    echo "Error: " . error_get_last()['message'] . "\n";
} else {
    $data = json_decode($response, true);
    echo "Response:\n";
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
}
