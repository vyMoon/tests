<?php

class Connection
{
    public $config = [
        'host' => 'localhost',
        'dbname' => 'tests',
        'user' => 'root',
        'pass' => '',
        'dbType' => 'mysql'
    ];

    public $dbConnection;

    function __construct() {
        $config = $this->config;
        $this->dbConnection = new PDO(
            $config['dbType'].':host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8', 
            $config['user'], $config['pass']
        );
    }
}