<?php
session_start();

function sendEvent($title, $content){
	$rcv = array(
		"OpenData" => "rob.tranquillo@gmx.de", //rob.tranquillo@gmx.de
		"c3d2" => "c3d2@lists.c3d2.de", //c3d2@lists.c3d2.de
	);

	$to = $rcv[$_SESSION["type"]];
	$header = "From: ".$_SESSION["mail"]."\r\nReply-To: ".$_SESSION["mail"]."\r\nX-Mailer: PHP/".phpversion();
	if(mail($to, $title, $content, $header)){
		echo "<p>Ihr Event wurde eingereicht</p>";
	}else{
		echo "<p>Es ist leider ein Fehler aufgetreten</p>";
	}
}

$challenge = $_POST["challenge"];
if($challenge == $_SESSION["challenge"]){
	sendEvent($_SESSION["title"], $_SESSION["xml"]);
}else	/* todo:move formatting tags to the receiver or drop 'em */
	echo "<p>Der eingegebene Key ist ung&uuml;ltig.</p>";
}

session_destroy();
