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
	echo $datestr;
	return $datestr;
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
	$filexml = fopen("event.xml", "w");
	fwrite($filexml, $varxml);
	fclose($filexml);
}
?>
<HTml>
<head>
<title>test</title>
<body>
<?php
//echo "&lt;event title=\"".$title."\"&gt;\n&lt;start&gt;".$startstring."&lt;/start&gt;\n&lt;end&gt;".$endstring."&lt;/end&gt;\n&lt;location&gt;".$location."&lt;/location&gt;\n&lt;link&gt;".$link."&lt;/link&gt;\n&lt;/event&gt;\n";
echo "<a href='event.xml'>View event.xml</a>";
?>
</body>
</html>
