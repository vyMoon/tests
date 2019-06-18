<?php
class Index
{
    private $tests;  // экземпляр класса tests
    private $users;  //  экземпляр класса users
    private $pdo;   // подключение к бд
    private $testsPerPage = 10;  // количество тестов, выводимых на страницу

    function __construct($pdo) {
        $this->pdo = $pdo;
        $this->tests = new Tests($pdo);
        $this->users = new Users($pdo);
    }

    public function display() {

        if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {

            if ($_GET['action'] != 'registration') {

                include './view/login.php';   // если пользователь не авторизован показываем форму авторизации

            } elseif($_GET['action'] = 'registration') {   // если пользователь запросил форму регистрации
                // формируем сообщение для пользователя
                if (isset($_GET['message'])) {
                    $message = $this->users->messages[$_GET['message']];
                } else {
                    $message = $this->users->messages[0];
                }
                
                include './view/registry.php';  // показываем  форму регистрации
            }

        } elseif(isset($_SESSION['user']) && isset($_SESSION['id'])) {   // если пользователь авторизовался
            if ($this->tests->countTests == 'error') {
                
                include 'error.html';  // если нет подключения к базе данных показываем ошибку

            } elseif (isset($_GET['test'])) {
                
                $currentTest = $_GET['test'];
                $checkTable = $this->tests->checkTable($currentTest); // проверяет наличие теста

                if (count($this->tests->checkTable($currentTest)) == 0) {  // если теста нет показвает ошибку
                    
                    header("HTTP/1.1 404 Not Found");
                    include './view/404.html';

                } else {  // если тест есть показывает вопросы теста

                    $questions = $this->tests->simpleInquiry("SELECT question_id, question_type, question_text, answers FROM `$currentTest`");
                    
                    include './view/test.php';
                }
    
            } elseif(isset($_GET['page'])) { // отображаем список тестов 
    
                if ($this->tests->countTests !== 'error') {                                  // если есть подключение к бд считает количество страци с тестами по умлочания показываем список из 10 тестов на странице
                    $rest = $this->tests->countTests % $this->testsPerPage;
                    $pages = ($this->tests->countTests - $rest) / $this->testsPerPage;
                    if ($rest > 0) {
                        $pages += 1;
                    }
                }

                $activeTests = $this->tests->testsListGetter($_GET['page'], $this->testsPerPage); // получаем тесты, который нужно вывести на страницу и подключаем страницу, отображающую тесты

                include './view/testsList.php';
    
            } elseif (isset($_GET['result'])){ // если пройден тест отображаем страницу с результатомтеста

                $currentTest = $_GET['result'];  // в переменной массива гет приходит имя выполняемого теста
                $answers = $this->tests->answersMaker($_POST);  //составляем из массива с ответами, массив готовый к проверке
                $correctAnswers = $this->tests->correctAnswersGetter($currentTest);  // получаем правильные отвыты и собираем массив, готовый к проверке 
                $report = $this->tests->testChecker($answers, $correctAnswers);  // проверяем

                $this->users->resultWriter($currentTest, $_SESSION['id'], $report);  // записываем результат в бд
    
                include './view/result.php';  // отобрааем результат пользователю
    
            } else {
                header('location: ./index.php?page=1');  // по умолчанию показываем первую страницу с тестами
            }
        }
    }

    private function testsListShower($activeTests) {  //отображает список тестов на странице 
        if ($activeTests != 0) {
            foreach ($activeTests as $testName) {

                echo '<li class="list-group-item"><a href="./index.php?test='.$testName['test_name'].'">' . $testName['test_name'] . '</a></li>';
                
            }
        }
    }

    private function showPages($pages) {  // отображает ссылки для листания списка тестов
        for ($i = 1; $i <= $pages; $i += 1) {

            if ($i == $_GET['page']) {
                echo '<li class="page-item active" aria-current="page"><a class="page-link" href="./index.php?page=' . $i . '">' . $i . '<span class="sr-only">(current)</span></a></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="./index.php?page=' . $i . '">' . $i . '</a></li>';
            }
        }
    }

    private function questionsShower($questions) {  // отобраает вопросы теста
        foreach($questions as $question) {

            echo '<h2>' . $question['question_id'] . ' ' . $question['question_text'] . '</h2>';

            if($question['question_type'] == 'single' || $question['question_type'] == 'multi') { // если вопрос требует чекбокса или радиокнопки

                $answers = explode($this->tests->separator, $question['answers']); // варианты ответа приходят в виде строки с разделителями
                
                if($question['question_type'] == 'multi') {  // отображает впорос, на который можно выбрать один и больше вариантов ответа

                    echo '<p>Выберете один или несколько вариантов ответа.</p>';
                    $count = 0; // счетчик нужен для того, чтобы каждый вариант получил уникальный атрибут name

                    foreach($answers as $answer) {  // формируем атрибут и выводим вопрос
                        $name = $question['question_id'] . '-' . $count; 
                        $count += 1;

                        echo '<div class="form-group form-check">';
                        echo '<input type="checkbox" name="' . $name . '" value="' . $answer . '">' . $answer;
                        echo '</div>';

                    }
                }
                if ($question['question_type'] == 'single') {  // если вопрос имеет только один правильный ответ отображает вопрос

                    echo '<p>Выберете один вариант ответа.</p>';
                    
                    foreach($answers as $answer) {

                        echo '<div class="form-check form-check-inline">';
                        echo '<input type="radio" name="' . $question['question_id'] . '" value="' . $answer . '">';
                        echo '<label class="form-check-label" >' . $answer . '</label>';
                        echo '</div>';
                        
                    }
                }

            }
            if($question['question_type'] == 'text') {  // отображает вопрос, ответ на который необходимо ввести в поле для ввода ответа

                echo '<div class="form-group">';
                echo '<label for="exampleFormControlTextarea1">Введите правильный ответ в поле.</label>';
                echo '<textarea class="form-control col-sm-4" rows="3" placeholder="Введите свой ответ здесь" rows="3" cols="45" name="' . $question['question_id'] . '"></textarea>';
                echo '</div>';
            }
        }
    }

    private function reustShower($report) {  // отображает результат пройденного теста

        echo '<h2 class="card-title">' . $report . '%</h2>';
        
    }
}