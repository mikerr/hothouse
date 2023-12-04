<?PHP
include "config.php";

// takes raw data from the request 
$json = file_get_contents('php://input');
// Converts it into a PHP object 
$data = json_decode($json, true);

$room = $data['room'];
$temp = $data['temperature'];
$hum = $data['humidity'];

$sql = "INSERT INTO `house_temps`(`Room`, `Temperature`, `Humidity`) VALUES ('$room','$temp','$hum')";

$result = $conn->query($sql);
 ?>
