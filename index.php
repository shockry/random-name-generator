<?php
include 'NameManager.php';

$nameManager = new NameManager();

echo $nameManager->getCodename();
?>

<form method="post" action="CallManager.php">
	<input type="text" name="adjective" placeholder="adjective">
	<input type="text" name="name" placeholder="name">
	<input type="submit" value="Send">
</form>