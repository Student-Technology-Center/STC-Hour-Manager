<?php
	/*	
		commentGenLogic.php
		
		Author: Bryan Holdt
		Date: December 4th, 2014

		This page takes a name, a start date, and an end date
		and returns to the user comments for their time sheet
		for the period they specified.

		Shifts that were never covered are left in the HR_DATA table
		where as shifts that have been covered get transferred into
		the SHIFT_ARCHIVE table.
	*/

	$con = mysqli_connect("localhost","root","<PASSWORD HERE>","hourTracker");
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$commentsFor = $_POST['commentName'];
	$s = strtotime($_POST['txtDateStart']);
	$dateStart = date('Y-m-d', $s);
	$e = strtotime($_POST['txtDateEnd']);
	$dateEnd = date('Y-m-d', $e);

	echo "<h2> Timesheet comments for " . $commentsFor . " from " . $dateStart . " to " . $dateEnd . "</h2></br>";

	if((strcasecmp($commentsFor,"everyone") == 0) || (strcasecmp($commentsFor,"all") == 0) || (strcasecmp($commentsFor,"David Hamiter") == 0)){
		$allGivenSql = "SELECT * FROM SHIFT_ARCHIVE WHERE date_of_shift >= '$dateStart' AND date_of_shift <= '$dateEnd' ORDER BY date_of_shift ASC";
		if($result = mysqli_query($con, $allGivenSql)){
			while ($row = mysqli_fetch_object($result)) {
				$employee_covering = $row->emp_covering;
				$employee_giving = $row->emp_giving;
				$date = date_create($row->date_of_shift);
				$fromTime = date_create($row->shift_start_time);
				$toTime = date_create($row->shift_end_time);
				$fromTimeAMPM = $row->start_am_pm;
				$toTimeAMPPM = $row->end_am_pm;

				echo $date->format('d/m/y') . "<i> " . $employee_covering . "</i> covered <i>" . $employee_giving . "</i> from " . 
						$fromTime->format('G:i') . " " .$fromTimeAMPM . " - " .
						$toTime->format('G:i') . " " . $toTimeAMPPM . "</br>"; 
			}
			mysqli_free_result($row);
		}

		$allGivenNotCovSql = "SELECT * FROM HR_DATA WHERE date_of_shift <= CURDATE() AND date_of_shift >= '$dateStart' AND date_of_shift <= '$dateEnd' ORDER BY date_of_shift ASC";
		if($result = mysqli_query($con, $allGivenNotCovSql)){
			while ($row = mysqli_fetch_object($result)) {
				$employee_name = $row->emp_name;
				$date = date_create($row->date_of_shift);
				$fromTime = date_create($row->shift_start_time);
				$toTime = date_create($row->shift_end_time);
				$fromTimeAMPM = $row->start_am_pm;
				$toTimeAMPPM = $row->end_am_pm;
				$reason = $row->reasoning;
				
				echo $date->format('d/m/y'). " <i>" . $employee_name . "</i> Gave up hours from " . $fromTime->format('G:i') . " " .$fromTimeAMPM .
				 " - " . $toTime->format('G:i') . " " . $toTimeAMPPM . " <b>Not Covered.</b> Reason: " . $reason . "</br>";
			}
			mysqli_free_result($row);
		}
	}



	/* Get the shifts you gave up */
	$givenSql = "SELECT * FROM SHIFT_ARCHIVE WHERE emp_giving = '$commentsFor' AND date_of_shift >= '$dateStart' AND date_of_shift <= '$dateEnd' ORDER BY date_of_shift ASC";
	if($result = mysqli_query($con, $givenSql)){
		while ($row = mysqli_fetch_object($result)) {
			$whoCoveredThisPerson = $row->emp_covering;
			$date = date_create($row->date_of_shift);
			$fromTime = date_create($row->shift_start_time);
			$toTime = date_create($row->shift_end_time);
			$fromTimeAMPM = $row->start_am_pm;
			$toTimeAMPPM = $row->end_am_pm;
			echo $date->format('d/m/y') . " Gave up hours " . $fromTime->format('G:i') . " " .$fromTimeAMPM .
			 " - " . $toTime->format('G:i') . " " . $toTimeAMPPM . " Covered by " . $whoCoveredThisPerson . ".</br>";
		}
		mysqli_free_result($row);
	}

	/* Shifts given up but not covered */
	$givenNotCovSql = "SELECT * FROM HR_DATA WHERE emp_name='$commentsFor' AND date_of_shift <= CURDATE() AND date_of_shift >= '$dateStart' AND date_of_shift <= '$dateEnd'";
	if($result = mysqli_query($con, $givenNotCovSql)){
		while ($row = mysqli_fetch_object($result)) {
			$date = date_create($row->date_of_shift);
			$fromTime = date_create($row->shift_start_time);
			$toTime = date_create($row->shift_end_time);
			$fromTimeAMPM = $row->start_am_pm;
			$toTimeAMPPM = $row->end_am_pm;
			$reason = $row->reasoning;
			echo $date->format('d/m/y') . " Gave up hours " . $fromTime->format('G:i') . " " .$fromTimeAMPM .
			 " - " . $toTime->format('G:i') . " " . $toTimeAMPPM . " <b>Not Covered.</b> Reason: " . $reason . "</br>";
		}
		mysqli_free_result($row);
	}

	/* Get the shifts you covered */
	$gotCoverSql = "SELECT * FROM SHIFT_ARCHIVE WHERE emp_covering='$commentsFor' AND date_of_shift >= '$dateStart' AND date_of_shift <= '$dateEnd' ORDER BY date_of_shift";

	if($result = mysqli_query($con, $gotCoverSql)){
		while ($row = mysqli_fetch_object($result)){
			$coveredWho = $row->emp_giving;
			$date =  date_create($row->date_of_shift);
			$fromTime = date_create($row->shift_start_time);
			$toTime =  date_create($row->shift_end_time);
			$fromTimeAMPM = $row->start_am_pm;
			$toTimeAMPPM = $row->end_am_pm;
			//echo "Covered " . $coveredWho . " on " . $date->format('M jS') .
			// " from " . $fromTime->format('G:i') . " " . $fromTimeAMPM . " to " . $toTime->format('G:i') . " " . $toTimeAMPPM . ". </br>";
			echo $date->format('d/m/y') . " Covered hours " . $fromTime->format('G:i') . 
			" " . $fromTimeAMPM . " to " . $toTime->format('G:i') . " " . $toTimeAMPPM . " for " . $coveredWho . ".</br>";

		}
		mysqli_free_result($row);
	}
	mysqli_close($con);
?>