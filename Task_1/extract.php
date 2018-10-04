<?php
echo "<h2>Vaše objednávka:</h2><br>";
echo "<p>Na jméno " . $_POST["first_name"] . " " . $_POST["surname"];
echo " s e-mailem " . $_POST["e-mail"] . "</p>";
echo "<p>Zvolen typ objednávky " . $_POST["type_of_order"] . ".</p>";
$comment = $_POST["comment"];
if (empty($comment)) {
    echo "<i>Bez poznámky</i>";
}   else {
    echo "Poznámka:<br><i>$comment</i>";
}
?>