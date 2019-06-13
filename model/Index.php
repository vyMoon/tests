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
            // echo $_GET['test'];
            include './view/test.php';
        } elseif(isset($_GET['page'])) {

            if ($this->tests->countTests !== 'error') {
                $rest = $this->tests->countTests % $this->testsPerPage;
                $pages = ($this->tests->countTests - $rest) / $this->testsPerPage;
                if ($rest > 0) {
                    $pages += 1;
                }
                // echo $pages;
            }
            $activeTests = $this->tests->testsListGetter($_GET['page'], $this->testsPerPage);
            // print_r($activeTests);
            include './view/testsList.php';
        }else {
            header('location: ./index.php?page=1');
        }
    }
}