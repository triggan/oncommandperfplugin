<?php 

// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.
session_start();

$servername = $_SESSION['ipaddress'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$clusaggrvol = explode("_",$_SESSION['clusaggrvol']);
$dbname = "netapp_performance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define a JSON arrya to return the results of the query
$json_return = array();
$json_return['cols'][] = array('id'=>"",'label' => "Date",'type' => 'date');
$json_return['cols'][] = array('id'=>"",'label' => "IOPs",'type' => 'number');

//If this is a volume run the following query.  If not (and an aggregate) run the ELSE query.
if(count($clusaggrvol) == 3){

	$sql = "SELECT
			AVG(totalOps) as iops,
			DATE_FORMAT(FROM_UNIXTIME((time * 0.001)), '%Y') AS myt_year,
                        DATE_FORMAT(FROM_UNIXTIME((time * 0.001)), '%m') AS myt_month,
                        DATE_FORMAT(FROM_UNIXTIME((time * 0.001)), '%d') AS myt_day,
                        DATE_FORMAT(FROM_UNIXTIME((time * 0.001)), '%H') AS myt_hour,
                        (DATE_FORMAT(FROM_UNIXTIME((time * 0.001)), '%i') DIV 10) AS myt_min
 		FROM sample_volume_".$clusaggrvol[2]."
 		WHERE
                        objid = ".$clusaggrvol[1]."
                GROUP BY `myt_year` , `myt_month` , `myt_day` , `myt_hour` , `myt_min`
                ORDER BY `myt_year` , `myt_month` , `myt_day` , `myt_hour` , `myt_min`";

	$result = $conn->query($sql);

	// Define a JSON arrya to return the results of the query
	$json_return = array();
	$json_return['cols'][] = array('id'=>"",'label' => "Date",'type' => 'date');
	$json_return['cols'][] = array('id'=>"",'label' => "IOPs",'type' => 'number');

	if ($result->num_rows > 0) {
    		// output data of each row
    		while($row = $result->fetch_assoc()) {
        		$json_return['rows'][] = array('c' => array(array('v' => "Date(".$row['myt_year'].", ".(((int)$row['myt_month'])-1).", ".$row['myt_day'].", ".$row['myt_hour'].", ".$row['myt_min']."0)"),array('v' => (int)$row['iops'])));
    		} //end while
	} else {
    		echo "0 results";
	} //end if

} elseif (count($clusaggrvol) == 2) {

        $sql = "SELECT
			AVG(`sample_aggregate_1`.`totalTransfers`) AS `iops`, 
			DATE_FORMAT(FROM_UNIXTIME((`sample_aggregate_1`.`time` * 0.001)), '%Y') AS `myt_year`,
    			DATE_FORMAT(FROM_UNIXTIME((`sample_aggregate_1`.`time` * 0.001)), '%m') AS `myt_month`,
   			DATE_FORMAT(FROM_UNIXTIME((`sample_aggregate_1`.`time` * 0.001)), '%d') AS `myt_day`,
    			DATE_FORMAT(FROM_UNIXTIME((`sample_aggregate_1`.`time` * 0.001)), '%H') AS `myt_hour`,
    			(DATE_FORMAT(FROM_UNIXTIME((`sample_aggregate_1`.`time` * 0.001)), '%i') DIV 10) AS `myt_min`
    		FROM
        		`sample_aggregate_1`
    		WHERE
        		(`sample_aggregate_".$clusaggrvol[0]."`.`objid` = ".$clusaggrvol[1].")
    		GROUP BY `myt_year` , `myt_month` , `myt_day` , `myt_hour` , `myt_min`
    		ORDER BY `myt_year` , `myt_month` , `myt_day` , `myt_hour` , `myt_min`";

        $result = $conn->query($sql);

        // Define a JSON arrya to return the results of the query
        $json_return = array();
        $json_return['cols'][] = array('id'=>"",'label' => "Date",'type' => 'date');
        $json_return['cols'][] = array('id'=>"",'label' => "IOPs",'type' => 'number');

        if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                        $json_return['rows'][] = array('c' => array(array('v' => "Date(".$row['myt_year'].", ".(((int)$row['myt_month'])-1).", ".$row['myt_day'].", ".$row['myt_hour'].", ".$row['myt_min']."0)"),array('v' => (int)$row['iops'])));
                } //end while
        } else {
                echo "0 results";
        } //end if

} else {
	echo "Something went terribly wrong!";
} //end if

$conn->close();
echo json_encode($json_return);

?>
