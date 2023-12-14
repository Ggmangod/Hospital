<?php
session_start(); // Start the session

// Function to read user credentials from the text file
function readUserCredentials() {
    $file = 'user_credentials.txt';
    $credentials = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $users = [];

    foreach ($credentials as $line) {
        $data = explode(':', $line);
        $users[$data[0]] = $data[1];
    }

    return $users;
}

// Function to save user credentials to the text file
function saveUserCredentials($username, $password) {
    $file = 'user_credentials.txt';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $line = "$username:$hashedPassword" . PHP_EOL;

    // Append the new user credentials to the file
    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

// Function to validate login credentials
function validateCredentials($username, $password) {
    $users = readUserCredentials();

    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        return true; // Authentication successful
    }
    return false; // Authentication failed
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Save new user credentials
        saveUserCredentials($username, $password);

        // Redirect to login page after successful registration
        header('Location: login.html');
        exit();
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate the submitted credentials for login
        if (validateCredentials($username, $password)) {
            // If the credentials are valid, set the session and redirect to the dashboard
            $_SESSION['username'] = $username;
            header('Location: hospital_S.html');
            exit();
        } else {
            // If credentials are invalid, redirect back to the login page with an error message
            header('Location: login.html?error=invalid_credentials');
            exit();
        }
    }
}
?>
