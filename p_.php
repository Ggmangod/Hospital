<?php
// Проверка, была ли отправлена форма с данными для входа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка данных на соответствие вашим критериям для входа
    // В данном примере проверяется, что имя пользователя и пароль равны 'correctUsername' и 'correctPassword'
    if ($username === 'correctUsername' && $password === 'correctPassword') {
        // Если вход успешен, выполните необходимые действия, например, установите сессию или отправьте пользователя на другую страницу
        // Пример установки сессии:
        session_start();
        $_SESSION['username'] = $username;

        // Затем перенаправьте пользователя на нужную страницу
        header('Location: hospital_S.html');
        exit();
    } else {
        // Если данные для входа неверны, можно выполнить действия по обработке ошибки
        // Например, перенаправить обратно на страницу входа с сообщением об ошибке
        header('Location: login.html?error=invalid_credentials');
        exit();
    }
}
?>
