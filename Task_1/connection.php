<?php
function openCon() {
    define('SQL_HOST', 'localhost');
    define('SQL_DBNAME', 'programDB');
    define('SQL_USERNAME', 'root');
    define('SQL_PASSWORD', '');
    
    $dsn = 'mysql:dbname=' . SQL_DBNAME . ';host=' . SQL_HOST . '';
    $user = SQL_USERNAME;
    $password = SQL_PASSWORD;
    try {
        $con = new PDO($dsn, $user, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
    return $con;
}

function closeCon($con) {
    $con = null;
}
?>