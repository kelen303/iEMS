<?php

class Chart{
	public function draw($data){

		echo "<script type=\"text/javascript\">
	var data = ".json_encode($data).";";

		// Push the data to the js array
		foreach ($data as $x_value => $y_value){
			//echo "data.push([".$x_value.",".$y_value."]);\n";
		}

		echo "	plotGraph(data); 
</script>";
	}
/*	public function draw($data){
		// Create script tag 
		echo "<script type="text/javascript">
	var data = [];";

		// Push the data to the js array
		foreach ($data as $x_value => $y_value){
			echo "data.push([".$x_value.",".$y_value."]);";
		}

		// Call the javascript to draw the graph
		echo "	plotGraph(data); 
				</script>";
	}*/
}
?>