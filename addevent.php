<?php
session_start();
function sanitize($string){
	$string = strip_tags($string);
	$string = str_replace("&", "&amp;", $string);
	$string = str_replace("\"", "&quot;", $string);
	$string = str_replace("'", "&apos;", $string);
	$string = str_replace("<", "&lt;", $string);
	$string = str_replace(">", "&gt;", $string);
	return $string;
}

function leadZero($nmb){
	if($nmb < 10){
		$nmb = "0".(string)$nmb;
		return $nmb;
	}else{
		$nmb = (string)$nmb;
		return $nmb;
	}
}

function dateArray($date, $time){
	$datearray = explode(".", $date);
	$timearray = explode(":", $time);
	$dt = [
		"day" => intval($datearray[0]),
		"month" => intval($datearray[1]),
		"year" => intval($datearray[2]),
		"hour" => intval($timearray[0]),
		"minute" => intval($timearray[1]),
	];
	$dt["day"] = leadZero($dt["day"]);
	$dt["month"] = leadZero($dt["month"]);
	$dt["year"] = (string)$dt["year"];
	$dt["hour"] = leadZero($dt["hour"]);
	$dt["minute"] = leadZero($dt["minute"]);
	return $dt;
}

function dateString($date){
	$datestr = $date["year"]."-".$date["month"]."-".$date["day"]."T".$date["hour"].":".$date["minute"].":00";
	return $datestr;
}

function challengeString(){
	$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$chstr = "";
	for($i = 0; $i < 8; $i++){
		$chstr .= $chars[rand(0, strlen($chars) - 1)];
	}
	return $chstr;
}

function sendChallenge($address, $subject){
	$subject .= " - Verifizierung";
	$body = challengeString();
	$header = "From: no-reply@offenesdresden.de\r\nX-Mailer: PHP/".phpversion();
	if(mail($address, $subject, $body, $header)){
		return $body;
	}else{
		echo "Es ist ein Fehler aufgetreten.\n";
	}
}

$title = sanitize($_POST["title"]);
$startdate = sanitize($_POST["startdate"]);
$starttime = sanitize($_POST["starttime"]);
$enddate = sanitize($_POST["enddate"]);
$endtime = sanitize($_POST["endtime"]);
$location = sanitize($_POST["location"]);
$type = $_POST["type"];
$link = sanitize($_POST["link"]);
$mail = sanitize($_POST["mail"]);
$start = dateArray($startdate, $starttime);
$end = dateArray($enddate, $endtime);
if(!$start or !$end){
	echo "<p>Es ist ein Eingabefehler aufgetreten.</p><a href='addevent.html'>Zurück</a>";
}else{
	$startstring = dateString($start);
	$endstring = dateString($end);
	$varxml = "<event title=\"".$title."\">\n<start>".$startstring."</start>\n<end>".$endstring."</end>\n<location>".$location."</location>\n<link>".$link."</link>\n<mail>".$mail."</mail>\n</event>\n";
	$challenge = sendChallenge($mail, $title);
	$_SESSION["challenge"] = $challenge;
	$_SESSION["title"] = $title;
	$_SESSION["xml"] = $varxml;
	$_SESSION["type"] = $type;
	$_SESSION["mail"] = $mail;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Event einreichen</title>
<body>
	<fieldset>
		<legend>Eventdaten</legend>
		<?php
		echo "<p><label>Titel:<br/>".$title."</label></p>";
		echo "<p><label>Start:<br/>".$start["day"].".".$start["month"].".".$start["year"]." ".$start["hour"].":".$start["minute"]."</label></p>";
		echo "<p><label>Ende: <br/>".$end["day"].".".$end["month"].".".$end["year"]." ".$end["hour"].":".$end["minute"]."</label></p>";
		echo "<p><label>Ort:<br/>".$location."</label></p>";
		echo "<p><label>Link:<br/>".$link."</label></p>";
		echo "<p><label>Kategorie: <br/>".$type."</label></p>";
		echo "<p><label>E-Mail:<br/>".$mail."</label></p>";
		?>
		<form action="challenge.php" method="post">
		<?php echo "<input type='hidden' name='sid' value='".session_id()."'/>";?>
		<p><label>Code:<br/><input type="text" name="challenge"/></label></p>
		<p><label><input type="submit" Value="Absenden"></label></p>
		</form>
	</fieldset>
</body>
</html>
