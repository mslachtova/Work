<?php

include 'parse_url.php';

function fillAFile($filename, $url) {
    $file = fopen($filename, "w");
    list($date, $cinemas, $programs) = fillParameters($url);
    $date_array = explode(" ", $date->nodeValue);
    $day = $date_array[0];
    $todays_date = $date_array[1];
    fwrite($file, chr(255).chr(254).mb_convert_encoding($day . "," . $todays_date . "\n", 'UTF-16LE', 'UTF-8'));
    
    $item_count = 1;
    foreach ($cinemas as $cinema) {
        fwrite($file, chr(255).chr(254).mb_convert_encoding($cinema->nodeValue . "\n", 'UTF-16LE', 'UTF-8'));
        $table = $programs->item($item_count);
        $films = $table->getElementsByTagName("th");
        foreach ($films as $film) {
            fwrite($file, chr(255).chr(254).mb_convert_encoding($film->nodeValue . "\n", 'UTF-16LE', 'UTF-8'));
        }
        fwrite($file, chr(255).chr(254).mb_convert_encoding("\n", 'UTF-16LE', 'UTF-8'));
        $item_count++;
    }
    fclose($file);
}

function download($filename) {
    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }
}

if(isset($_POST["submit"])){
    $url = "https://www.csfd.cz/kino/?district-filter=55";
    $filename = "program.csv";
    fillAFile($filename, $url);
    download($filename);
}
?>

<!DOCTYPE HTML>
<html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<form method="post">
<input id="download" type="submit" name="submit" value="Stáhnout program kin">
</form>

</body>
</html>