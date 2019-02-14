<?php
include 'connection.php';
include 'parse_url.php';

function createTables($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS Films (
                film_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL default '',
                release_year INT UNSIGNED NOT NULL
            );";
    
    $sql .= "CREATE TABLE IF NOT EXISTS Showtimes (
                date DATE NOT NULL,
                cinema_name VARCHAR(100) NOT NULL default '',
                film_id INT UNSIGNED NOT NULL,
                PRIMARY KEY (date, cinema_name, film_id)
            );";
    
    $conn->exec($sql);
}

function insertFilm($conn, $film) {
    list($name, $year) = explode(" (", rtrim($film->nodeValue, ")"));
    $exists = $conn->prepare("SELECT film_id FROM Films WHERE name = '$name' AND release_year = $year;");
    $exists->execute();
    if ($exists->rowCount() == 0) {
        $sql = "INSERT INTO Films (name, release_year) VALUES ('$name', $year)";
        $conn->exec($sql);
        return $conn->lastInsertId();
    }
    return $exists->fetch(PDO::FETCH_OBJ)->film_id;
}

function insertInTables($conn, $url) {
    list($date, $cinemas, $programs) = fillParameters($url);
    $date_format = explode(" ", $date->nodeValue)[1];
    $item_counter = 1;
    foreach ($cinemas as $cinema) {
        $films = $programs->item($item_counter)->getElementsByTagName("th");
        foreach ($films as $film) {
            $film_id = insertFilm($conn, $film);
            $sql = "INSERT IGNORE INTO Showtimes (date, cinema_name, film_id)
                    VALUES (STR_TO_DATE('$date_format', '%d.%m.%Y'), '$cinema->nodeValue', $film_id)";
            $conn->exec($sql);
        }
        $item_counter++;
    }
}

function printData($conn) {
    try {
        $conn->query("SELECT 1 FROM Showtimes LIMIT 1");
        $conn->query("SELECT 1 FROM Films LIMIT 1");
    } catch (Exception $e) {
        return;
    }
    $dates = $conn->query("SELECT DISTINCT date FROM Showtimes");
    while ($date = $dates->fetch(PDO::FETCH_NUM)) {
        echo date_format(date_create($date[0]), 'l d. m. Y') . "<br>";
        $cinemas = $conn->query("SELECT DISTINCT cinema_name FROM Showtimes WHERE date = '$date[0]'");
        while ($cinema = $cinemas->fetch(PDO::FETCH_NUM)) {
            echo "<h2>$cinema[0]</h2>";
            $films = $conn->query("SELECT DISTINCT name, release_year FROM Showtimes NATURAL INNER JOIN Films WHERE date = '$date[0]'
                                    AND cinema_name='$cinema[0]'");
            while ($film = $films->fetch(PDO::FETCH_NUM)) {
                echo "<hr>$film[0], $film[1]</h3><br>";
            }
            echo "<br>";
        }
    }
}

if (isset($_POST["save"])) {
    try {
        $conn = OpenCon();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        createTables($conn);
        $url = "https://www.csfd.cz/kino/?district-filter=55";
        insertInTables($conn, $url);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
    CloseCon($conn);
}

if (isset($_POST["print"])) {
    try {
        $conn = OpenCon();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        printData($conn);
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
    CloseCon($conn);
}

?>

<!DOCTYPE HTML>
<html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<form method="post">
<input type="submit" name="save" value="Uložit informace do databáze">
<br><br>
<input type="submit" name="print" value="Zobrazit data z databáze">
<br>
</form>

</body>
</html>


