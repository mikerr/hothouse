<?PHP 
$servername = "localhost";
$username = "user";
$password = "password";
$dbname = "database"; 
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
        die("Connection Failed" . $conn->connect_error);
    }
?>
