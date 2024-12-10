<?php
header('Content-Type: application/json');

// File to store user data
$userFile = 'registered_users.json';

function getUsersList() {
    global $userFile;
    
    // Check if file exists
    if (!file_exists($userFile)) {
        return [];
    }
    
    // Read and decode JSON file
    $jsonContent = file_get_contents($userFile);
    return json_decode($jsonContent, true) ?: [];
}

// Check if the request is a GET request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        $users = getUsersList();
        
        $response = [
            'status' => 'success',
            'users' => $users
        ];
        
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error fetching users: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>
