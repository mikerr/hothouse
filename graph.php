<?PHP

// display temperature statst

include "config.php";

$sql = "SELECT DISTINCT room FROM `house_temps` ";
$result = $conn->query($sql);

$rooms = array();
    while($row = mysqli_fetch_array($result)){
        $room = $row['room'];
        $rooms[] = $room;
    }

$datapoints = array();
$x = 0;
foreach ($rooms as $room) {
    //echo "<h3>" . $room . "</h3>";
  
    $sql = "SELECT * FROM `house_temps` WHERE room = '$room' ORDER BY time";
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


 ?>

<!DOCTYPE HTML>
<html>
<head> 
<script>
window.onload = function () {
 
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
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>   
