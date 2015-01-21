<?php

if($_GET['debug']) {
	echo "<html><body>";
}

echo "<form name=\"chooseCluster\" action=\"getAggrVolList.php\" method=\"post\">";
echo "<p>Choose the cluster to examine:</p>";
echo "<select name=\"cluster\">";
echo "<option selected=\"selected\">Select a cluster...</option>";

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.
session_start();

$_SESSION['username'] = $_POST['username'];
$_SESSION['password'] = $_POST['password'];
$_SESSION['ipaddress'] = $_POST['ipaddress'];

$servername = $_SESSION['ipaddress'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$dbname = "netapp_model";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT objid,name FROM cluster;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	echo "<option value=\"".$row['objid']."\">".$row['name']."</option>";
        }
} else {
    echo "0 results";
}

echo "</select>";
echo "<input name=\"Submit\"  type=\"submit\" value=\"Login\"/>";
echo "</form>";
$conn->close();

if($_GET['debug']) {
        echo "</body></html>";
}


?>
