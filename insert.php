<?php


if(isset($_GET["pulse"])) {
	$pulse = $_GET["pulse"];
	$oxygen = $_GET["oxygen"];
	$person_id = $_GET["person_id"];
	$link = mysqli_connect("localhost", "root", "", "emergency_calls");

    	if($link === false){
        	die("ERROR: Could not connect. " . mysqli_connect_error());
    	}

    	$sql = "INSERT INTO patient_data (pulse, oxygen, person_id) VALUES ('$pulse' , '$oxygen', '$person_id')";

    	if(mysqli_query($link, $sql)){
        	echo "Records added successfully.";
    	} else{
        	echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    	}

    	mysqli_close($link);
}
?>
