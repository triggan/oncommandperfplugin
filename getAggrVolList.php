<?php

echo "<form name=\"chooseStore\" action=\"graph.php\" method=\"post\">";
echo "<p>Choose the aggregate or volume to examine:</p><br/><br/>";
echo "<h3>Aggregates</h3>";

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.
session_start();

$servername = $_SESSION['ipaddress'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$dbname = "netapp_model";

$cluster = $_POST['cluster'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT objid,name FROM aggregate WHERE clusterId=".$cluster;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	echo "<input type=\"radio\" name=\"clusaggrvol\" value=\"".$cluster."_".$row['objid']."\">".$row['name']."</br>";;
        }
} else {
    echo "0 results";
}

echo "<br/>";
echo "<h3>Volumes</h3>";

$sql = "SELECT volume.objid as vobjid,volume.name as vname,vserver.objid as svmobjid,vserver.name as svmname FROM volume,vserver WHERE volume.clusterId=".$cluster." AND (volume.vserverId = vserver.objid)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<input type=\"radio\" name=\"clusaggrvol\" value=\"".$cluster."_".$row['vobjid']."_".$row['svmobjid']."\">".$row['svmname'].": ".$row['vname']."</br>";;
        }
} else {
    echo "0 results";
}

echo "<input name=\"Submit\" type=\"submit\" value=\"Submit\"/>";
echo "</form>";
$conn->close();


?>
