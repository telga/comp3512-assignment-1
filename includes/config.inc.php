<?php 

//Have to use the cur dir path appended to one folder out to data folder.
//Only doing this because I like to organize in folders, I get if config is in root it is easier but I prefer not.
define (constant_name: 'DB_SQLITE', value: __DIR__ . '/../data/stocks.db');

//throw exceptions for api calls, else just die.
function getDbConnection() { 
    try {
        $pdo = new PDO(dsn: 'sqlite:' . DB_SQLITE);
        $pdo -> setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        if (headers_sent()) {
            die("Connection error: " . $e -> getMessage());
        } else {
            throw $e;
        }
    }
}

    

