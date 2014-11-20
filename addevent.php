<?php
$title = $_POST["title"];
$date = $_POST["date"];
$time = $_POST["time"];
$location = $_POST["location"];
$link = $_POST["link"];
?>
<html>
<head>
<title>test</title>
<body>
<?php
echo $title.$time.$date.$location.$link;
?>
</body>
</html>
