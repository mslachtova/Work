<?php
function openCon() {
    define('SQL_HOST', 'localhost');
    define('SQL_DBNAME', 'programDB');
    define('SQL_USERNAME', 'root');
    define('SQL_PASSWORD', '');
    
    $dsn = 'mysql:dbname=' . SQL_DBNAME . ';host=' . SQL_HOST . '';
    $user = SQL_USERNAME;
    $password = SQL_PASSWORD;
    $con = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $con;
}

function closeCon($con) {
    $con = null;
}
?>