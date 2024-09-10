<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'phppdo-ajax';
$port = '3307';

$dsn = 'mysql:host='. $host.';port='. $port.';dbname='. $dbname;

$pdo = new PDO($dsn, $user, $password);
$pdo -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
