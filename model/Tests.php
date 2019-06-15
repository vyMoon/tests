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
    
}