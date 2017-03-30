<!DOCTYPE html>
<html lang="en">
	<head>
		<title>STC Consent Staff Tools</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/style.css">
		<!-- Latest compiled and minified CSS -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    	<!-- Optional theme -->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Staff Tools</h1>
			</div>
			<?php
		         //set up mysql connection
		         $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
		         //select database
		         mysql_select_db("consentFrm", $link) or die(mysql_error());
		         $sql = "SELECT uid, dateStamp, wNum, type FROM USER_DATA";
				 $result = $conn->query($sql);
				 if ($result->num_rows > 0) {
    				// output data of each row
    				while($row = $result->fetch_assoc()) {
        				echo "uid: " . $row["uid"]. " - Name: " . $row["dateStamp"]. " " . $row["wNum"]. "<br>";
    			}
				} else {
				echo "0 results";
				}
				$conn->close();
			?>
		</div>
	</body>
</html>