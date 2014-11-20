<?php
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

function sendMail($subject, $body){
	$to = "user@localhost";
	if(mail($to, $subject, $body)){
		echo "Ihr Termin wurde versandt.\n";
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
$link = sanitize($_POST["link"]);
$start = dateArray($startdate, $starttime);
$end = dateArray($enddate, $endtime);
if(!$start or !$end){
	echo "<p>Falsche Eingabe</p><a href='addevent.html'>Zurück</a>";
}else{
	$startstring = dateString($start);
	$endstring = dateString($end);
	$varxml = "<event title=\"".$title."\">\n<start>".$startstring."</start>\n<end>".$endstring."</end>\n<location>".$location."</location>\n<link>".$link."</link>\n</event>\n";
	/*$filexml = fopen("event.xml", "w");
	fwrite($filexml, $varxml);
	fclose($filexml);*/
	sendMail($title, $varxml);
}
?>
<HTml>
<head>
<title>Termin einreichen</title>
<body>
</body>
</html>
