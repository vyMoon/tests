<?php

class Tests
{
    private $pdo;  // подкючение к бд
    public $countTests;  // содержит в себе количество тестов, или ошибку
    public $separator = '//-//';  // разделитель, используется в строке, в которой храняться ответы

    function __construct($pdo) {
        $this->pdo = $pdo;

        $countTests = $this->simpleInquiry("SELECT COUNT(*) FROM `tests`", $pdo);  // получаем количество вопросов

        if ( isset($countTests[0]['COUNT(*)']) ) {
            $this->countTests = $countTests[0]['COUNT(*)'];
        } else {
            $this->countTests = 'error';
        }

    }

    public function simpleInquiry($inquiry) {  // функция, выполняющая запрос в бд
        $stmt = $this->pdo->prepare($inquiry);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function testsListGetter($page, $limit) { // получает список тестов в соотвествии с количество, которое необбходимо отобразить на странице
        $offset = ($page * $limit) - $limit;
        $inquiry = 'SELECT test_name FROM `tests` LIMIT ' . $limit . ' OFFSET ' . $offset;
        return $this->simpleInquiry($inquiry);
    }

    public function correctAnswersGetter($testName) {  // получает правильные ответы на тест

        $inquiry = "SELECT correct_answer, question_type, question_id FROM `$testName`" ;
        $data = $this->simpleInquiry($inquiry);
        
        $correctAnswers = [];  // формирует массив правильных ответов
        foreach($data as $answer) {
            if ($answer['question_type'] == 'multi') {  // если вопрос имеет несколько верных ответов формируем вложенный массив с ответами на такой впорос
                $answer['correct_answer'] = explode($this->separator, $answer['correct_answer']);
                $correctAnswers[$answer['question_id']] = $answer['correct_answer'];
            } else {
                $correctAnswers[$answer['question_id']] = $answer['correct_answer'];  // иначе записывается как отдельный элмент массива
            }
        }

        return $correctAnswers;
    }

    public function testChecker($answers, $correctAnswers) {  // выполняте проверку теста и возвращает результат в процентах

        $total = count($correctAnswers);  // считаем количество правильных ответов
        $correct = 0;  // счетчик количества правильных ответов, которые дал пользователь

        foreach($answers as $key => $answer) {
            if (gettype($answer) == 'array') { // если вопрос имеет не один вариант ответа
                
                if (count($answers[$key]) != count($correctAnswers[$key])) {  // если количество ответов на такой вопрос и кольичество правильных ответов данных пользователем не совпадает
                    continue;  // вопрос считает не отвеченным не верно
                } else {
                    $localCount = 0; // проверка ответов
                    foreach($answer as $answerPart) {
                        if ( in_array($answerPart, $correctAnswers[$key]) ) { // если кадые ответ содержится в массиве верных ответов и количество сивпадает то впорос засчитывается как решенный верно
                            $localCount++;
                        }
                    }
                    if ($localCount == count($correctAnswers[$key])) {
                        $correct++;
                    }

                }
            } else {
                if ($answers[$key] == $correctAnswers[$key]) { // если ответ дин и ион совпадает, вопрос засчиывается как решенный верно
                    $correct++;
                }
            }
        }
        
        $report = round($correct / ($total / 100)); // результат демонстрируется в процентах. каждый вопрос имеет одинаковый вес в процентах
        return $report;
    }

    public function answersMaker($answers) {  // форматирует массив пришедши отвеов от пользователя в массив, котовый к проверке

        $formattedAnswers = [];
        foreach($answers as $key => $answer) {
            
            if(strrpos($key, '-')) {  // вопросы имеющие несколько вараинтов ответа имеют  - в индексе
                $correictIndex = (int)substr($key, 0, strrpos($key, '-')); //получаем номер вопроса
                
                if (gettype($formattedAnswers[$correictIndex]) !== 'array') { // если в массиве с ответами элемент массива еще не массив - создаем массив
                    
                    $formattedAnswers[$correictIndex] = [];
                }

                array_push($formattedAnswers[$correictIndex], $answer); // добавляем ответы в массив

            } else {
                $formattedAnswers[$key] = $answer;  // если вопрос имеет только один ответ добавляем ответ как элмент массива
            }
        }
        
        return $formattedAnswers;

    }

    public function checkTable($tableName) {  // проверяет если в бд таблица с запрошеным тестом
        $inquiry = "SHOW TABLES FROM tests LIKE '$tableName'";
        $answer = $this->simpleInquiry($inquiry);

        return $this->simpleInquiry($inquiry);
    }
    
}