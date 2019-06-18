<?php

class Users 
{
    private $pdo;  // подключение к бд
    private $resultSeparator = '//--//';  // разделитель между результатами выполнения разных тестов
    private $separator = '-/-';  // разделитель в стрроке информации о выполнении теста дата-/-название теста-/-результат

    public $messages = [  // массив сообщений, которые демонстрируются пользователюв при регистрации
        'Введите ваши логин и пароль',
        'Пользователь с таким логином уже зарегистрирован'
    ];

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function simpleInquiry($inquiry) { // выполняет запрос к бд
        $stmt = $this->pdo->prepare($inquiry);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function userChecker($login) { // проверяет занят ли логин выполняется при регистрациинового пользователя
        $inquiry = "SELECT user_id FROM `users` WHERE `user_name` = '$login'";
        $answer = $this->simpleInquiry($inquiry);
        
        if (isset($answer[0]['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
    public function userRegistration($name, $pass) { // вносит данные нового пользователя в таблицу
        $inquiry = "INSERT INTO `users` (`user_name`, `user_pass`) VALUES ('$name','$pass')";
        $this->simpleInquiry($inquiry);
    }

    public function login($name, $pass) { // запрашивает данные пользователя при авторизации

        $inquiry = "SELECT `user_id`, `user_name`, `user_pass` FROM `users` WHERE user_name = :name";
        $stmt = $this->pdo->prepare($inquiry);
        $stmt -> execute(["name" => $name]);
        $answer = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($answer[0]['user_name'] == $name && $answer[0]["user_pass"] == $pass) { // если данные совпадают заполняем сессию
            
            $_SESSION['id'] = $answer[0]['user_id'];
            $_SESSION['user'] = $answer[0]['user_name'];
            
        }
    }

    public function resultWriter($testName, $user, $result) { // записывает результат выполнения теста
        $date = date('c');

        $note = $date . $this->separator . $testName . $this->separator . $result . $this->resultSeparator; // формирует строку дата-/-название теста-/-результат
        
        $inquiry = "SELECT `user_tests` FROM `users` WHERE `user_id` = '$user'";  // запрашивает строку с ужевыполненными тестами
        $data = $this->simpleInquiry($inquiry);
        $userTests = $data[0]['user_tests'] . $note; // добавляет результат
        
        $inquiry = "UPDATE `users` SET`user_tests`= '$userTests' WHERE `user_id` = '$user'"; // одновляет строку выполненных тестов
        $this->simpleInquiry($inquiry);
    }
    
}