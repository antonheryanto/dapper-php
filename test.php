<?php

require './dapper.php';
require './conf.php';

$pdo = new PDO(DBNAME, DBUSER, DBPASS, [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_CASE => PDO::CASE_NATURAL,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);
$db = new Dapper($pdo);
$o = $db->query("select * from courses limit 1");
print_r($o[0]);
