<?php
header('Content-Type: application/json');

// File to store user data
$userFile = 'registered_users.json';

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function saveUser($userData) {
    global $userFile;
    
    // Read existing users
    $users = file_exists($userFile) ? json_decode(file_get_contents($userFile), true) : [];
    
    // Add new user
    $users[] = $userData;
    
    // Save updated users list
    file_put_contents($userFile, json_encode($users));
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Validate and sanitize inputs
    $fullName = validateInput($_POST['fullName'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = validateInput($_POST['phone'] ?? '');
    $age = filter_var($_POST['age'] ?? '', FILTER_VALIDATE_INT);
    $gender = validateInput($_POST['gender'] ?? '');

    // Perform validation checks
    if (empty($fullName)) {
        $errors[] = "Full name is required";
    }
    if (!$email) {
        $errors[] = "Invalid email format";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    if (!$age || $age < 18 || $age > 100) {
        $errors[] = "Invalid age";
    }
    if (empty($gender)) {
        $errors[] = "Gender is required";
    }

    // Respond with JSON
    if (empty($errors)) {
        $userData = [
            'fullName' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'age' => $age,
            'gender' => $gender
        ];
        
        // Save user
        saveUser($userData);
        
        $response = [
            'status' => 'success',
            'data' => $userData
        ];
        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => implode(', ', $errors)
        ];
        echo json_encode($response);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>
