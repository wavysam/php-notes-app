<?php

define("DB_SERVER","mysql:host=localhost;port=3306;dbname=notes_app");
define("DB_USER","root");
define("DB_PASSWORD","");

try {
    $pdo = new PDO(DB_SERVER, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR". $e->getMessage());
}