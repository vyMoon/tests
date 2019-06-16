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
            // print_r($questions);
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
            // print_r($activeTests);
            include './view/testsList.php';

        } elseif (isset($_GET['result'])){
            $currentTest = $_GET['result'];
            $answers = $this->tests->answersMaker($_POST);
            // print_r($answers);
            $correctAnswers = $this->tests->correctAnswersGetter($currentTest);
            // print_r($correctAnswers);
            $report = $this->tests->testChecker($answers, $correctAnswers);
            // print_r($report);

            include './view/result.php';

        } else {
            header('location: ./index.php?page=1');
        }
    }

    private function testsListShower($activeTests) {
        if ($activeTests != 0) {
            foreach ($activeTests as $testName) {
                // <li class="list-group-item">Vestibulum at eros</li>
                // echo '<p><a href="./index.php?test='.$testName['test_name'].'">' . $testName['test_name'] . '</a></p>';

                echo '<li class="list-group-item"><a href="./index.php?test='.$testName['test_name'].'">' . $testName['test_name'] . '</a></li>';
                
            }
        }

        // for ($i = 1; $i <= $pages; $i += 1) {
        //     echo $i > $pages;
        //     if ($i == $_GET['page']) {
        //         echo '<span>' . $i . '</span>';
        //     } else {
        //         echo '<a href="./index.php?page=' . $i . '">' . $i . '</a>';
        //     }
        // }
    }

    private function showPages($pages) {
        for ($i = 1; $i <= $pages; $i += 1) {

            if ($i == $_GET['page']) {
                echo '<li class="page-item active" aria-current="page"><a class="page-link" href="./index.php?page=' . $i . '">' . $i . '<span class="sr-only">(current)</span></a></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="./index.php?page=' . $i . '">' . $i . '</a></li>';
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
                    echo '<p>Выберете один или несколько вариантов ответа.</p>';
                    $count = 0;
                    foreach($answers as $answer) {
                        $name = $question['question_id'] . '-' . $count;
                        $count += 1;
                        // echo '<p><input required type="checkbox" name="' . $question['question_id'] . '" value="' . $answer . '">' . $answer . '</p>';
                        echo '<div class="form-group form-check">';
                        echo '<input type="checkbox" name="' . $name . '" value="' . $answer . '">' . $answer;
                        echo '</div>';

                        // echo '<div class="form-group form-check">';
                        // echo '<input type="checkbox" name="' . $name . '" value="' . $answer . '>';
                        // echo '<label class="form-check-label">' . $answer . '</label>';
                        // echo '</div>';
                    }
                }
                if ($question['question_type'] == 'single') {
                    echo '<p>Выберете один вариант ответа.</p>';
                    $count;
                    foreach($answers as $answer) {
                        // echo '<p><input required name="' . $question['question_id'] . '" type="radio" value="' . $answer . '">' . $answer . '</p>';
                        // echo '<input required name="' . $question['question_id'] . '" type="radio" value="' . $answer . '">' . $answer;

                        echo '<div class="form-check form-check-inline">';
                        echo '<input type="radio" name="' . $question['question_id'] . '" value="' . $answer . '">';
                        echo '<label class="form-check-label" >' . $answer . '</label>';
                        echo '</div>';
                        $count += 1;
                    }
                }

            }
            if($question['question_type'] == 'text') {
                // echo '<p>Введите правильный ответ в поле.</p>';
                // echo '<textarea class="col-sm-6" required placeholder="Введите свой ответ здесь" rows="3" cols="45" name="' . $question['question_id'] . '"></textarea>';

                echo '<div class="form-group">';
                echo '<label for="exampleFormControlTextarea1">Введите правильный ответ в поле.</label>';
                echo '<textarea class="form-control col-sm-4" rows="3" placeholder="Введите свой ответ здесь" rows="3" cols="45" name="' . $question['question_id'] . '"></textarea>';
                echo '</div>';
            }
        }
    }

    private function reustShower($report) {
        echo '<h2 class="card-title">' . $report . '%</h2>';
    }
}