<?php

try {
    $response = file_get_contents('test.json');
    
    if ($response === false || empty($response)) {
        throw new Exception('API request failed or returned empty response');
    }

    $data = json_decode($response, true);

    // echo'<pre>';
    // print_r($data);
    // echo'</pre>';

    if (!is_array($data)) {
        throw new Exception('Invalid JSON format');
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON Decode Error: ' . json_last_error_msg());
    }

    $requiredFields = ['title', 'body', 'userId', 'email'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            error_log("Missing field: $field");
        }
    }

    $title = $data[0]['title'] ?? 'Unknown';
    $body = $data[0]['body'] ?? 'Not provided';
    $user_id = $data[0]['userId'] ?? 0;
    $email = $data[0]['email'] ?? 'N/A';

    echo '<pre>';
    print_r(compact('title', 'body', 'user_id', 'email'));
    echo '</pre>';

} catch (Exception $e) {
    error_log('API Error: ' . $e->getMessage());
    echo 'An error occurred while processing the request.';
}

?>
