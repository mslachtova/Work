

<?php 
if(isset($_POST["submit"])) {
    $text =  iconv("utf-8", "ascii//TRANSLIT", $_POST["string"]); 
    $array = explode("#", $text);
    foreach ($array as $word){
        if ($word !== $array[0]) {
            echo "&";
        }
        echo $word;
    }
    echo "<br>";
    echo implode("!", $array); 
}

?>

<!DOCTYPE HTML>
<html>  
<body>

<form action="text.php" method="post">
<input type="text" name="string"><br>
<input type="submit" name="submit" value="Odeslat">
</form>


</body>
</html>
