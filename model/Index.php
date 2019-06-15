<?php
class Index
{
    private $tests;
    private $pdo;
    private $testsPerPage = 10;

    function __construct($pdo) {
        $this->pdo = $pdo;
        $this->tests = new Tests($pdo);
    }

    public function display() {
        // print_r($this->tests->countTests % 10);
        // print_r( ($this->tests->countTests - ($this->tests->countTests % 10)) /10 );
        if (isset($_GET['test'])) {
            $currentTest = $_GET['test'];
            $questions = $this->tests->simpleInquiry("SELECT question_id, question_type, question_text, answers FROM `$currentTest`");
            print_r($questions);
            include './view/test.php';

        } elseif(isset($_GET['page'])) {

            if ($this->tests->countTests !== 'error') {
                $rest = $this->tests->countTests % $this->testsPerPage;
                $pages = ($this->tests->countTests - $rest) / $this->testsPerPage;
                if ($rest > 0) {
                    $pages += 1;
                }
            }
            $activeTests = $this->tests->testsListGetter($_GET['page'], $this->testsPerPage);
            print_r($activeTests);
            include './view/testsList.php';

        } elseif (isset($_GET['result'])){
            echo $_GET['result'];
            print_r($_POST);
            $an = in_array($_POST[1], $_POST);

            echo gettype($an);

        } else {
            header('location: ./index.php?page=1');
        }
    }

    private function testsListShower($activeTests) {
        if ($activeTests != 0) {
            foreach ($activeTests as $testName) {
                echo '<p><a href="./index.php?test='.$testName['test_name'].'">' . $testName['test_name'] . '</a></p>';
                
            }
        }

        for ($i = 1; $i <= $pages; $i += 1) {
            echo $i > $pages;
            if ($i == $_GET['page']) {
                echo '<span>' . $i . '</span>';
            } else {
                echo '<a href="./index.php?page=' . $i . '">' . $i . '</a>';
            }
        }
    }

    private function questionsShower($questions) {
        foreach($questions as $question) {

            echo '<h2>' . $question['question_id'] . ' ' . $question['question_text'] . '</h2>';

            // echo $question['question_type'];
            if($question['question_type'] == 'single' || $question['question_type'] == 'multi') {
                $answers = explode($this->tests->separator, $question['answers']);
                // print_r($answers);
                if($question['question_type'] == 'multi') {
                    $count = 0;
                    foreach($answers as $answer) {
                        $name = $question['question_id'] . '-' . $count;
                        $count += 1;
                        // echo '<p><input required type="checkbox" name="' . $question['question_id'] . '" value="' . $answer . '">' . $answer . '</p>';
                        echo '<input type="checkbox" name="' . $name . '" value="' . $answer . '">' . $answer;
                    }
                }
                if ($question['question_type'] == 'single') {
                    foreach($answers as $answer) {
                        // echo '<p><input required name="' . $question['question_id'] . '" type="radio" value="' . $answer . '">' . $answer . '</p>';
                        echo '<input required name="' . $question['question_id'] . '" type="radio" value="' . $answer . '">' . $answer;
                    }
                }

            }
            if($question['question_type'] == 'text') {
                echo '<textarea required placeholder="Введите свой ответ здесь" rows="3" cols="45" name="' . $question['question_id'] . '"></textarea>';
            }
        }
    }
}