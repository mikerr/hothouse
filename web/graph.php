<?PHP

// display temperature & humidity stats

include "config.php";

$sql = "SELECT DISTINCT room FROM `house_temps` ";
$result = $conn->query($sql);

$rooms = array();
while($row = mysqli_fetch_array($result)){
        $room = $row['room'];
        $rooms[] = $room;
    }

$day = $_GET['day'];
$dayrange = "";
if ($day == "today") $dayrange = " AND time > curdate()";    
if ($day == "yesterday") $dayrange = "AND date(`time`) = curdate() - 1";    


//temperatures
$datapoints = array();
$x = 0;
foreach ($rooms as $room) {
    $sql = "SELECT * FROM `house_temps` WHERE room = '$room' " . $dayrange . " ORDER BY time";
    $result = $conn->query($sql);

    $dataPoints[$x] = array();
    while($row = mysqli_fetch_array($result)){
        $temp =  $row['Temperature'];
        $phpDate = $row['Time'];
        $phpTimestamp = strtotime($phpDate);
        $time = $phpTimestamp * 1000;
        array_push($dataPoints[$x], array("x" => $time, "y" => $temp));
    }
    $x = $x + 1;
}
// humidity
$humdatapoints = array();
$x = 0;
foreach ($rooms as $room) {
    $sql = "SELECT * FROM `house_temps` WHERE room = '$room' " . $dayrange . " ORDER BY time";
    $result = $conn->query($sql);

    $humdataPoints[$x] = array();
    while($row = mysqli_fetch_array($result)){
        $temp =  $row['Humidity'];
        $phpDate = $row['Time'];
        $phpTimestamp = strtotime($phpDate);
        $time = $phpTimestamp * 1000;
        array_push($humdataPoints[$x], array("x" => $time, "y" => $temp));
    }
    $x = $x + 1;
}
 ?>

<!DOCTYPE HTML>
<html>
<head> 
<script>
window.onload = function () {
//temperatures 
var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	animationEnabled: false,
	zoomEnabled: true,
	title: {text: "Room Temperatures"},
	data: [
    <?PHP
    $x = 0;
    foreach ($rooms as $room) {
    ?>
    {
        name: "<?php echo $room; ?>",
		showInLegend: true,
		type: "line",
        xValueType: "dateTime",     
		dataPoints: <?php echo json_encode($dataPoints[$x], JSON_NUMERIC_CHECK); ?>
	},
    <?PHP 
	$x = $x + 1; 
    } ?>
    {}
    ]
});
chart.render();

 // humidity
 var chart = new CanvasJS.Chart("chartContainer2", {
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	animationEnabled: false,
	zoomEnabled: true,
	title: {text: "Room Humidity"},
	data: [
    <?PHP
    $x = 0;
    foreach ($rooms as $room) {
    ?>
    {
        name: "<?php echo $room; ?>",
		showInLegend: true,
		type: "line",
        xValueType: "dateTime",     
		dataPoints: <?php echo json_encode($humdataPoints[$x], JSON_NUMERIC_CHECK); ?>
	},
    <?PHP
    $x = $x + 1;
    } 
    ?>
    {}
    ]
});

chart.render();
}
</script>
</head>
<body>
<center>
<h1>House</h1>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<p><p>
<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<p><p>
<a href = "?day=all">all</a> 
<a href = "?day=yesterday">yesterday</a> 
<a href = "?day=today">today</a> 
</body>
</html>   
