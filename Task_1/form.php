<!DOCTYPE HTML>
<html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>

<h2>Objednávka</h2>
<form action="extract.php" method="post">
Typ objednávky:<br>
<input type="radio" name="type_of_order" value="osobní odběr na pobočce"> Osobní odběr na pobočce<br>
<input type="radio" name="type_of_order" value="poslat poštou"> Poslat poštou<br>
<br>
Jméno: <input type="text" name="first_name"><br>
Příjmení: <input type="text" name="surname"><br>
E-mail: <input type="text" name="e-mail"><br>
<br>
Poznámka:<br>
<textarea name="comment" rows="5" cols="50"></textarea><br>
<br>
<input type="checkbox" required name="agreement"> Souhlasím s podmínkami
<span style="color:red">*</span>
<br><br>
<input type="submit" value="Odeslat">
</form>

</body>
</html>
