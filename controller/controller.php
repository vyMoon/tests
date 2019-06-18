<?php

// error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
// ini_set('display_errors', 1);

spl_autoload_register(function ($class) {
	$dir = '../model/' . $class . '.php';
	if (file_exists($dir)) {
		require_once $dir;
	}
});

session_start();

$connection = new Connection();
$users = new Users($connection->dbConnection);

if (isset($_POST['newLogin']) && isset($_POST['newPass'])) { // выполняет регистрацию нового пользователя

    $login = $_POST['newLogin'];
    $pass = $_POST['newPass'];
    $answer = $users->userChecker($login);  // проверяет занято ли имя пользователя возвращает true - false
    
    if ($answer) { // если имя занято  возвращем на страницу регистрауии и показываем сообщение что пользователь с тауим именем зарегистрированн
        header('location: ../index.php?message=1&action=registration');
    } else {
        
        $users->userRegistration($login, $pass); // если имя свободно - регистрируем пользователя и отправляем на главную
        header('location: ../index.php');
    }

} elseif (isset($_POST['login']) && isset($_POST['pass'])) { // если пользователь ваторизуется

    $users->login($_POST['login'], $_POST['pass']); // авторизуем пользователя и отправляем на главную
    header('location: ../index.php');

} elseif (isset($_GET['action'])) { // если пользователь выходит из системы очищаем сессию

    if ($_GET['action'] = 'logaut') {
        session_destroy();
        header('location: ../index.php');
    }
}