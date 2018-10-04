<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
 $myarray = array(4, 6, 9, 5, 0);
 rsort($myarray);
 $len = count($myarray);
 for ($i = 0; $i < $len; $i++) {
     echo "$myarray[$i]";
     if ($i != $len - 1) {
         echo ", ";
     }
 }
 $assoc_array = array("jmeno" => "Tomáš", "prijmeni" => "Polešovský",
     "email" => "mujEmail@gmail.cz", "telefon" => "506775123");
 foreach ($assoc_array as $item => $value){
     echo '<br> Hodnota komponenty formulare "' . $item. ' je "' . $value. '"';
 }
?>


</body>
</html>