<?php
/*
	partialInsert.php

	Author: Bryan Holdt
	Date: May 2014

	This file is responsible for taking the form data from finalConfirm.php and inserting
	the data into the database. 

	This file can determine which of the 3 cases a user's desired shift is.

	Case 1: Same start -- Diff End
	Case 2: Diff start -- Same End
	Case 3: Diff start -- Diff end
*/
	function addTwelve($time, $ampm) {
		if ($ampm == "AM")
			return 0;
		else
			if(intval(substr($time, 0, 2)) == 12){
				return 0;
			}else{ 
				return 12;
			}
		}

	function getHourDiff($start, $startAMPM, $end, $endAMPM) {
		$strt = intval(substr($start, 0, 2)) + addTwelve($start, $startAMPM);
		$en = intval(substr($end, 0,2)) + addTwelve($end, $endAMPM);
		$diff = $en - $strt;
		return $diff;
	}


	session_start();
	
	$con = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
	mysql_select_db("hourTracker", $con) or die(mysql_error());

	$covName = $_POST['covName'];
	$datID = $_POST['datID'];
	$desStart = $_POST['inputDesiredStart']; //Desired start
	if(substr($desStart,0, 2) != "10" || substr($desStart,0, 2) != "11" || substr($desStart,0, 2) != "12"){
		$desStart = "0" . $desStart;
	}
	$desEnd = $_POST['inputDesiredEnd'];     //Desired End
	if(substr($desEnd,0, 2) != "10" || substr($desEnd,0, 2) != "11" || substr($desEnd,0, 2) != "12"){
		$desEnd = "0" . $desEnd;
	}
	$desStartAMPM = $_POST['desiredStartAMPMdropdown'];
	$desEndAMPM = $_POST['desiredEndAMPMdropdown'];

	$result = mysql_query("SELECT * FROM HR_DATA WHERE id=$datID", $con) or die(mysql_error());

	//While loop is probably not necessary since there SHOULD only be one result returned.
	while ($row = mysql_fetch_array($result)){
		$startTime = $row['shift_start_time']; //Original start
		$startTime = substr($startTime, 0, -3);
		$startTime = ltrim($startTime, '0');
		$startTimeAMPM = $row['start_am_pm']; //Original AMPM for start
		$endTime = $row['shift_end_time']; //Original End
		$endTime = substr($endTime, 0, -3);
		$endTime = ltrim($endTime, '0');
		$endTimeAMPM = $row['end_am_pm']; //Original AMPM for End
		$sDate = ($row['date_of_shift']); //The date -- Should remain the same
		$oldNameSameName = ($row['emp_name']); //The original name of the emp who gave up hours
		$reas = ($row['reasoning']); //Original reasoning persists
		$origDT = ($row['datetime_shift_given_up']);
		$origDTime = date_create_from_format('d/M/Y:H:i:s', $origDT);
		$removeduid = ($row['uid']); //universal id of person that posted the shift
	}

	$desStart = ltrim($desStart, '0');
	$desEnd = ltrim($desEnd, '0');

//Case 1 : Same start -- Diff End
	if($desStart == $startTime && $desEnd != $endTime){
		echo "Same start times! Different Ends!</br>";
 		//Insert new shift
		$sql = "INSERT INTO HR_DATA (date_of_shift, emp_name, shift_start_time, start_am_pm, shift_end_time, end_am_pm, reasoning, datetime_shift_given_up, uid)
		VALUES
		('$sDate','$oldNameSameName','$desEnd','$desEndAMPM', '$endTime', '$endTimeAMPM','$reas', NOW(), '$removeduid')";

		mysql_query($sql, $con) or die(mysql_error());


		//Add to archive
		$archiveSQL = "INSERT INTO SHIFT_ARCHIVE(date_of_shift, emp_giving, emp_covering, shift_start_time, shift_end_time, start_am_pm, end_am_pm)
					VALUES('$sDate', '$oldNameSameName', '$covName','$startTime', '$desEnd', '$startTimeAMPM', '$desEndAMPM')";
		mysql_query($archiveSQL, $con);			
		
		//Delete old shift
		$delquery = "DELETE FROM HR_DATA WHERE id=$datID";
		mysql_query($delquery, $con) or die(mysql_error());

		//Update stats
		$sql = "INSERT INTO EMP_STATS(emp_name, num_shifts_covered) VALUES ('$covName', '1') ON DUPLICATE key UPDATE num_shifts_covered = num_shifts_covered + 1 ";
		mysql_query($sql, $con) or die(mysql_error());
		
		$difference = getHourDiff($startTime, $startTimeAMPM, $desEnd, $desEndAMPM);
		$sql = "INSERT INTO EMP_STATS(emp_name, hours_covered) VALUES('$covName', '$difference') ON DUPLICATE key UPDATE hours_covered = hours_covered + '$difference'";
		mysql_query($sql, $con) or die(mysql_error());
		mysql_close($con);

		echo "<h1 align=\"center\">COMPLETE</h1>";
	//Case 2: Diff start -- Same End
	}else if($desStart != $startTime && $desEnd == $endTime){   
		echo "Different start times! Same Ends!</br>";
		//Insert new shift (From desStart --> $endTime)
		$sql = "INSERT INTO HR_DATA (date_of_shift, emp_name, shift_start_time, start_am_pm, shift_end_time, end_am_pm, reasoning, datetime_shift_given_up, uid)
		VALUES
		('$sDate','$oldNameSameName','$startTime','$startTimeAMPM', '$desStart', '$desStartAMPM','$reas', NOW(), '$removeduid')";

		mysql_query($sql, $con) or die(mysql_error());

		//Add to archive
		$archiveSQL = "INSERT INTO SHIFT_ARCHIVE(date_of_shift, emp_giving, emp_covering, shift_start_time, shift_end_time, start_am_pm, end_am_pm)
					VALUES('$sDate', '$oldNameSameName', '$covName','$desStart', '$endTime', '$desStartAMPM', '$endTimeAMPM')";
		mysql_query($archiveSQL, $con);		

		//Delete old shift
		$delquery = "DELETE FROM HR_DATA WHERE id=$datID";
		mysql_query($delquery, $con) or die(mysql_error());

		//Update stats
		$sql = "INSERT INTO EMP_STATS(emp_name, num_shifts_covered) VALUES ('$covName', '1') ON DUPLICATE key UPDATE num_shifts_covered = num_shifts_covered + 1 ";
		mysql_query($sql, $con) or die(mysql_error());


		$sql = "INSERT INTO EMP_STATS(emp_name, hours_covered) VALUES('$covName', '$difference') ON DUPLICATE key UPDATE hours_covered = hours_covered + '$difference'";
		mysql_query($sql, $con) or die(mysql_error());
		mysql_close($con);

		echo "<h1 align=\"center\">COMPLETE</h1>";
//Case 3: Diff start -- Diff end		
	}else if($desStart != $startTime && $desEnd != $endTime){
		echo "Different start times and end times</br>";
		echo "</br>";
		
		//Create 2 new shifts
			//From origStart - $desStart
		$sql = "INSERT INTO HR_DATA (date_of_shift, emp_name, shift_start_time, start_am_pm, shift_end_time, end_am_pm, reasoning, datetime_shift_given_up, uid)
		VALUES
		('$sDate','$oldNameSameName','$startTime','$startTimeAMPM', '$desStart', '$desStartAMPM','$reas', NOW(), '$removeduid')";

		mysql_query($sql, $con) or die(mysql_error());

		//From $desEnd - $endTime
		$sql = $sql="INSERT INTO HR_DATA (date_of_shift, emp_name, shift_start_time, start_am_pm, shift_end_time, end_am_pm, reasoning, datetime_shift_given_up, uid)
		VALUES
		('$sDate','$oldNameSameName','$desEnd','$desEndAMPM', '$endTime', '$endTimeAMPM','$reas', NOW(), '$removeduid')";

		mysql_query($sql, $con) or die(mysql_error());

		//Add to archive
		$archiveSQL = "INSERT INTO SHIFT_ARCHIVE(date_of_shift, emp_giving, emp_covering, shift_start_time, shift_end_time, start_am_pm, end_am_pm)
					VALUES('$sDate', '$oldNameSameName', '$covName','$desStart', '$desEnd', '$desStartAMPM', '$desEndAMPM')";
		mysql_query($archiveSQL, $con);

		//Delete original
		$delquery = "DELETE FROM HR_DATA WHERE id=$datID";
		mysql_query($delquery, $con) or die(mysql_error());

		//Update stats
		$sql = "INSERT INTO EMP_STATS(emp_name, num_shifts_covered) VALUES ('$covName', '1') ON DUPLICATE key UPDATE num_shifts_covered = num_shifts_covered + 1 ";
		mysql_query($sql, $con) or die(mysql_error());

		$difference = getHourDiff($desStart, $desStartAMPM, $desEnd, $desEndAMPM);
		$sql = "INSERT INTO EMP_STATS(emp_name, hours_covered) VALUES('$covName', '$difference') ON DUPLICATE key UPDATE hours_covered = hours_covered + '$difference'";
		mysql_query($sql, $con) or die(mysql_error());
		mysql_close($con);

		echo "<h1 align=\"center\">COMPLETE</h1>";
	}else{ //Handle Exact cover?
		echo "Covering the entire shift eh?";
		header("refresh:1; url=http://192.241.201.209/stcHourManager/index.php?");
	}


///////////////////
//AUTO-SEND EMAIL//
///////////////////

	error_reporting(E_STRICT);
	date_default_timezone_set('America/Los_Angeles');
	require_once('../../../usr/share/php/libphp-phpmailer/class.phpmailer.php');

//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = "Part of your shift has been covered!</br></br><b>Date: </b>" . $sDate . "
	</br><b>Shift Start: </b>" . $desStart . " " . $desStartAMPM . "
	</br><b>Shift End: </b>" . $desEnd . " " . $desEndAMPM . "

	</br></br><i>You should thank <b>" . $covName . "</b> for covering your shift!</i></br>
	--STC";

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mail.yourdomain.com"; // SMTP server
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = "stchours@gmail.com";  // GMAIL username
$mail->Password   = "<PASSWORD HERE>";            // GMAIL password
$mail->SetFrom("stchours@gmail.com", "STC hours");

$mail->Subject    = "Shift Partially Covered";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);

$address = $removeduid."@wwu.edu";
$mail->AddAddress($address, $removeduid);

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
}else {
	echo "<marquee><span style=\"font-size:20px;font-weight:bold;\">Message sent!</span></marquee>";
}


echo "<h1 align=\"center\">Thank you. Your request has been received.</h1>";
header( "refresh:3;url=http://192.241.201.209/stcHourManager/");
exit;
?>