<?php
session_start();

// Функция для чтения учетных данных из файла
function readUserCredentials() {
    $file = 'user_credentials.txt';
    $credentials = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $users = [];

    foreach ($credentials as $line) {
        $data = explode(':', $line);
        $users[$data[0]] = [
            'password' => $data[1],
            'email' => $data[2],
            'phone' => $data[3]
        ];
    }

    return $users;
}

// Функция для сохранения учетных данных в файл
function saveUserCredentials($username, $password, $email, $phone) {
    $file = 'user_credentials.txt';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $line = "$username:$hashedPassword:$email:$phone" . PHP_EOL;

    // Добавление новых учетных данных в файл
    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Сохранение новых учетных данных
        saveUserCredentials($username, $password, $email, $phone);

        // Отправка ответа в формате JSON об успешной регистрации
        echo json_encode(['success' => true]);
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $users = readUserCredentials();

        if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
            $_SESSION['username'] = $username;
            // Отправка ответа в формате JSON об успешном входе
            echo json_encode(['success' => true]);
        } else {
            // Отправка ответа в формате JSON о неудачном входе
            echo json_encode(['success' => false, 'message' => 'Неверное имя пользователя или пароль']);
        }
    }
}
?>
