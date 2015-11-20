<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Cascade Flat , Responsive Bootstrap 3.0 Admin Template</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Loading Bootstrap -->
  <link href="css/bootstrap.css" rel="stylesheet">

  <!-- Loading Stylesheets -->    
  <link href="css/font-awesome.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet" type="text/css"> 
   
   <?php 
     $pieces = explode('/',$_SERVER['REQUEST_URI']);  
  $page=end($pieces); 
if(strpos($page,"extended-modals") !== false ) { ?>
   <link href="css/bootstrap-modal-bs3fix.css" rel="stylesheet" type="text/css"> 
   <?php } ?>

  <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss">
  
  <!-- Loading Custom Stylesheets -->    
  <link href="css/custom.css" rel="stylesheet">

  <link rel="shortcut icon" href="images/favicon.ico">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
      <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <![endif]-->
    </head>
<body>

	<div class="row">


	<div class="col-md-12">
		<div class="panel panel-cascade">
			<div class="panel-heading">
				<h3 class="panel-title">
					Selection Charts
					<p class="pull-right text-info">You selected: <span id="selection"></span></p>
				</h3>
			</div>
			<div class="panel-body">

				<div class="demo-container">
					<div id="placeholder" class="demo-placeholder"></div>
				</div>

			</div>
		</div>
	</div>
</div>

	<!-- Load JS here for Faster site load =============================-->
	<script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="js/less-1.5.0.min.js"></script>
	<script src="js/jquery.ui.touch-punch.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-select.js"></script>
	<script src="js/bootstrap-switch.js"></script>
	<script src="js/jquery.tagsinput.js"></script>
	<script src="js/jquery.placeholder.js"></script>
	<script src="js/bootstrap-typeahead.js"></script>
	<script src="js/application.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/jquery.sortable.js"></script>
	<script type="text/javascript" src="js/jquery.gritter.js"></script>
	<script src="js/jquery.nicescroll.min.js"></script>
	<script src="js/skylo.js"></script>
	<script src="js/prettify.min.js"></script>
	<script src="js/jquery.noty.js"></script>
	<script src="js/bic_calendar.js"></script>
	<script src="js/jquery.accordion.js"></script>
	<script src="js/theme-options.js"></script>

	<script src="js/bootstrap-progressbar.js"></script>
	<script src="js/bootstrap-progressbar-custom.js"></script>
	<script src="js/bootstrap-colorpicker.min.js"></script>
	<script src="js/bootstrap-colorpicker-custom.js"></script>

	<!-- Charts  =============================-->
	<script src="js/charts/jquery.flot.js"></script>
	<script src="js/charts/jquery.flot.resize.js"></script>
	<script src="js/charts/jquery.flot.stack.js"></script>
	<script src="js/charts/jquery.flot.selection.js"></script>
	<script src="js/charts/flot-joe.js"></script>

	<?php
		include "php/chart.php";
		

		function getData(){
			$servername = "localhost";
			$username = "root";
			$password = "Fresno5328";
			$dbname = "test";
			$data = array();

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);

			// Check connection
			if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			} 
			$sql = "SELECT * from data";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
					//echo "[".$row["date"].",".$row["info"]."],";
					//$tmp = array($row["date"],$row["info"]);
					array_push($data, array($row["date"],$row["info"]));
				}
			}
			$conn->close();
			return $data;
		}


		$graph = new Chart();
		$tmpData = getData();

		$graph->draw($tmpData);
	?>



</body>
</html>