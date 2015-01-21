<?php

session_start();

$_SESSION['clusaggrvol'] = $_POST['clusaggrvol'];

//echo "cluster variable count: ".count(s

?>


<html>
  <head>
    	<script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>	
    	<script type="text/javascript">
 	// Load the Visualization API and the controls package.
      	google.load('visualization', '1.0', {'packages':['controls']});
	// google.load('visualization', '1', {packages: ['corechart']});
    	google.setOnLoadCallback(drawChart);

    function drawChart() {

	var jsonData = $.ajax({
          url: "getData.php",
          dataType:"json",
          async: false
          }).responseText;

      	// Create our data table out of JSON data loaded from server.
      	var data = new google.visualization.DataTable(jsonData);
	var myDateFormatter = new google.visualization.DateFormat({pattern: "MMM d, yyyy H:m"});
	myDateFormatter.format(data,0);

	// Create a dashboard.
        var dashboard = new google.visualization.Dashboard(
            document.getElementById('dashboard_div'));

	// Create a range slider, passing some options
        var lineRangeSlider = new google.visualization.ControlWrapper({
		 'containerId': 'slider_div',
      		 'controlType': 'ChartRangeFilter',
		 'options':{
		 'filterColumnLabel': 'Date'}
        });

      var chart = new google.visualization.ChartWrapper({
        	'chartType': 'LineChart',
		'options' : {
			'height': 600
		},
		'containerId': 'ex0'});

	// Establish dependencies, declaring that 'filter' drives 'pieChart',
        // so that the pie chart will only display entries that are let through
        // given the chosen slider range.
        dashboard.bind(lineRangeSlider,chart);

      dashboard.draw(data);

    }
	</script>
  </head>
  <body>
    <div id="dashboard_div">
	<div id="ex0"></div>
	<div id="slider_div"></div>
	</div>
  </body>
</html>

