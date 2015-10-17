<?php

$db = null;

if (empty($database)) {
    die('no username and password to connect database');
}

try {
    $defaults = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $db = new PDO('mysql:host=' . $database['host'] . ';dbname=' . $database['dbname'], $database['username'], $database['password'], $defaults);
} catch (PDOException $e) {
    die("Erreur: à la ligne:" . $e->getLine() . "on a l'erreur:" . $e->getMessage());
}
