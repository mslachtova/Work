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

if (isset($_POST["save"])) {
    try {
        $conn = OpenCon();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        createTables($conn);
        $url = "https://www.csfd.cz/kino/?district-filter=55";
        insertInTables($conn, $url);
    } catch (PDOException $e) {
        //echo "here";
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
<input type="submit" onclick="showButton()" name="save" value="Uložit informace do databáze">
<br><br>
<div id="hidden" style="display:none;">
	<input type="button" name="print" value="Zobrazit data z databáze">
</div>
</form>

<script>
function showButton() {
	document.getElementById("hidden").style.display = "block";
}
</script>
</body>
</html>
