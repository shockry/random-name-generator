<?php
include 'NameManager.php';
if (isset($_POST['adjective']) || isset($_POST['name'])) {
	$nameManager = new NameManager();
	echo $nameManager->saveCodename();
}