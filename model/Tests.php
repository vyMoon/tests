<?php

class Tests
{
    private $pdo;
    public $countTests;
    public $separator = '//-//';

    function __construct($pdo) {
        $this->pdo = $pdo;

        $countTests = $this->simpleInquiry("SELECT COUNT(*) FROM `tests`", $pdo);

        if ( isset($countTests[0]['COUNT(*)']) ) {
            $this->countTests = $countTests[0]['COUNT(*)'];
        } else {
            $this->countTests = 'error';
        }

    }

    public function simpleInquiry($inquiry) {
        $stmt = $this->pdo->prepare($inquiry);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function testsListGetter($page, $limit) {
        $offset = ($page * $limit) - $limit;
        $inquiry = 'SELECT test_name FROM `tests` LIMIT ' . $limit . ' OFFSET ' . $offset;
        return $this->simpleInquiry($inquiry);
    }

    public function correctAnswersGetter($testName) {
        $inquiry = "SELECT correct_answer, question_type, question_id FROM `$testName`" ;
        // echo $inquiry;
        $data = $this->simpleInquiry($inquiry);
        // print_r($data);
        $correctAnswers = [];
        foreach($data as $answer) {
            if ($answer['question_type'] == 'multi') {
                $answer['correct_answer'] = explode($this->separator, $answer['correct_answer']);
                $correctAnswers[$answer['question_id']] = $answer['correct_answer'];
            } else {
                $correctAnswers[$answer['question_id']] = $answer['correct_answer'];
            }
        }

        return $correctAnswers;
    }

    public function testChecker($answers, $correctAnswers) {
        // print_r($answers);
        // print_r($correctAnswers);
        // $report = [];
        $total = count($correctAnswers);
        $correct = 0;

        foreach($answers as $key => $answer) {
            if (gettype($answer) == 'array') {
                // echo 'array';
                if (count($answers[$key]) != count($correctAnswers[$key])) {
                    continue;
                } else {
                    $localCount = 0;
                    foreach($answer as $answerPart) {
                        if ( in_array($answerPart, $correctAnswers[$key]) ) {
                            $localCount++;
                        }
                    }
                    if ($localCount == count($correctAnswers[$key])) {
                        $correct++;
                    }

                }
            } else {
                if ($answers[$key] == $correctAnswers[$key]) {
                    $correct++;
                }
            }
            // echo gettype($answer);
            // if ($answers[$key] == $correctAnswers[$key]) {
            //     echo '123456789';
            // }
        }
        // echo $correct;

        $report = $correct / ($total / 100);
        return $report;
    }

    public function answersMaker($answers) {

        $formattedAnswers = [];
        foreach($answers as $key => $answer) {
            // echo $key;
            if(strrpos($key, '-')) {
                $correictIndex = (int)substr($key, 0, strrpos($key, '-'));
                // echo 'uuuuu';
                if (gettype($formattedAnswers[$correictIndex]) !== 'array') {
                    // echo 'i made array';
                    $formattedAnswers[$correictIndex] = [];
                }

                array_push($formattedAnswers[$correictIndex], $answer);

            } else {
                $formattedAnswers[$key] = $answer;
            }
        }
        // print_r($formattedAnswers);
        return $formattedAnswers;

    }
    
}