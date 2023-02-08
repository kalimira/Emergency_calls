<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- css -->
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="buttonstyle.css">

		<title>Emergency calls</title>
	</head>
	<body>
<?php
    include "functions.php"; 
    $labelDate = date('Y-m-d');
		
?>
  <div class="person-data">
                <form action="index.php" method="get" >
                <input type="hidden" name="status" value="1">
                <input type="submit" name="measure" value="GET MEASUREMENTS!" class="button grad transition">
                </form>
		<table>
			<h1>Patient data</h1>
			<tr>
					<th>Date</th>
					<th>Pulse</th>
					<th>Oxygen</th>
				</tr>
<?php 
                        if (isset($_GET['measure'])) { 
                            $command = escapeshellcmd('C:\xampp\htdocs\hospital\test.py');
                            $output = shell_exec($command);
                            header("Location: index.php");
}
			if (!isset($_POST['searchbtn'])) { 
?>
				<div class="form-container">
					<form class = "dateFilter" action="index.php" method="POST" autocomplete="off">
						<label for="start">Start date:</label>
						<input type="date" id="start" name="start" value="<?php echo $labelDate ?>" min="2021-01-01" max="<?php echo $labelDate ?>">
									
						<label for="end">End date:</label>
						<input type="date" id="end" name="end" value="<?php echo $labelDate ?>" min="2021-01-01" max="<?php echo $labelDate ?>">
							
						<button name="searchbtn" action="index.php">Search</button>
					</form>	
				</div>
<?php
				while($row = mysqli_fetch_array($dailyrecords)): 
?>
					<tr>
							<td><?php echo $row['datetime']; ?></td>
							<td><?php echo $row['pulse']; ?></td>
							<td><?php echo $row['oxygen']; ?></td>
					</tr>                     	
<?php 
				endwhile;			
?>
<?php
				}
				else {
					$dateFrom = date('Y-m-d', strtotime($_POST['start']));
					$dateTo = date('Y-m-d', strtotime($_POST['end']));
?>
					<div class="form-container">
						<form class = "dateFilter" action="index.php" method="POST" autocomplete="off">
							<label for="start">Start date:</label>
							<input type="date" id="start" name="start" value="<?php echo $dateFrom ?>" min="2021-01-01" max="<?php echo $labelDate ?>">
									
							<label for="end">End date:</label>
							<input type="date" id="end" name="end" value="<?php echo $dateTo ?>" min="2021-01-01" max="<?php echo $labelDate ?>">
							
							<button name="searchbtn" action="index.php">Search</button>
						</form>
					</div>
<?php
					while($row=mysqli_fetch_array($records)){
						if (date('Y-m-d', strtotime($row['datetime'])) >= $dateFrom && date('Y-m-d', strtotime($row['datetime'])) <= $dateTo){
?>
							<tr>
								<td><?php echo $row['datetime']; ?></td>
								<td><?php echo $row['pulse']; ?></td>
								<td><?php echo $row['oxygen']; ?></td>
							</tr>
<?php 
						}
					} 
				}
?>
			</table>
						
			</div>
		<div class="chart-container">        
<?php
			include 'newchart.php';
?>
		</div>
	</body>
</html>