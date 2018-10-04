<?php
include 'connection.php';
include 'cinema_program.php';

function createTables($conn) {
    $sql = "CREATE TABLE Date (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                day VARCHAR(10) NOT NULL default '',
                date VARCHAR(10) NOT NULL default ''
            );";
    $sql .= "CREATE TABLE Cinemas (
                cinema_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL default ''
            );";
    $sql .= "CREATE TABLE Films (
                film_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL default ''
            );";
    $sql .= "CREATE TABLE Cinema_film (
                cinema_id INT UNSIGNED,
                film_id INT UNSIGNED,
                PRIMARY KEY (cinema_id, film_id)
            );";
    $conn->exec($sql);    
}

function insertCinema($conn, $line, $line_with_cinema) {
    if (!$line_with_cinema) {
        return false;  
    }
    $sql = "INSERT INTO Cinemas (name) VALUES ($line)";
    $conn->exec($sql);
    return true;
}

function insertFilmAndUpdate($conn, $line, $last_cinema) {
    $id_of_film = 0;
    echo "HERE!<br>";
    $exist = $conn->prepare("SELECT film_id FROM Films WHERE name = '$line';");
    echo "HERE!<br>";
    $exist->execute();
    if ($exist->rowCount() == 0) {
        $sql = "INSERT INTO Films (name) VALUES ($line)";
        $conn->exec($sql);
        $id_of_film = $conn->lastInsertId();
    } else {
        $get_id = $exist->fetch(PDO::FETCH_OBJ);
        $id_of_film = $get_id->film_id;
    }
    $exist = $conn->prepare("SELECT * FROM Cinema_film WHERE
                            cinema_id = '$last_cinema' AND film_id = '$id_of_film'");
    $exist->execute();
    if ($exist->rowCount() == 0) {
        $sql = "INSERT INTO Cinema_film (cinema_id, film_id) VALUES ($last_cinema, $id_of_film)";
        $conn->exec($sql);
    }
}

function insertInTables($conn, $file) {
    $line = fgets($file);
    $date = explode(",", $line);
    echo $line . "<br>";
    $sql = "INSERT INTO Date (day, date) VALUES (?, ?)";
    $sqlquery = $conn->prepare($sql);
    $sqlquery->execute([$date[0], $date[1]]);
    echo "HERE!<br>";
    $line = fgets($file);
    $sql = "INSERT INTO Cinema (name)
                VALUES ($line)";
    $conn->exec($sql);
    $last_cinema = $conn->lastInsertId();
    $line_with_cinema = false;
    while(!feof($file)) {
        $line = fgets($file);
        if (insertCinema($conn, $line, $line_with_cinema)) {
            $last_cinema = $conn->lastInsertId();
            $line_with_cinema = false;
        } else {
            if ($line == "\n") {
                $line_with_cinema = true;
            } else {
                insertFilmAndUpdate($conn, $line, $last_cinema);
            }
        }
    }
}

if (isset($_POST["save"])) {
    try {
        $conn = OpenCon();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        createTables($conn);
        $url = "https://www.csfd.cz/kino/?district-filter=55";
        $filename = "program.csv";
        fillAFile($filename, $url);
        $file = fopen($filename, "r");
        insertInTables($conn, $file);
        fclose($file);
            
    } catch (PDOException $e) {
        echo "here";
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
document.getElementById("download").style.display = "none";
function showButton() {
	document.getElementById("hidden").style.display = "block";
	document.getElementById("download").style.display  = "none";
}
</script>
</body>
</html>
