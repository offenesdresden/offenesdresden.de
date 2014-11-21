<?php
session_start();

function sendEvent($title, $content){
	$to = "user@localhost";
	if(mail($to, $title, $content)){
		echo "<p>Ihr Event wurde eingereicht</p>";
	}else{
		echo "<p>Es ist leider ein Fehler aufgetreten</p>";
	}
}

$challenge = $_POST["challenge"];
if($challenge == $_SESSION["challenge"]){
	sendEvent($_SESSION["title"], $_SESSION["xml"]);
}else{
	echo "<p>Der eingegebene Key ist ungültig.</p>";
}

session_destroy();
