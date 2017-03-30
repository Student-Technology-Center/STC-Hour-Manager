<?php
/*
	insert.php

	Author: Bryan Holdt
	Date: May 2014

	This file is responsible for taking the form data from give.php and inserting
	the data into the database.
*/

 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  session_start();

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


	$con = mysqli_connect("localhost","root","<PASSWORD HERE>","hourTracker");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$covName = $_POST["inputName"];
	$date = $_POST['txtDate'];
  $sDate = date_create($_POST['txtDate']);
  $dayOfWeek = $sDate->format('l');
	$inputName = $_POST['inputName'];
	$inputStart = $_POST['inputStart'];
	$inputStartAMPM = $_POST['StartAMPMdropdown'];
	$inputEnd = $_POST['inputEnd'];
	$inputEndAMPM = $_POST['EndAMPMdropdown'];
	$inputReasoning = $_POST['inputReasoning'];
	$uid = $_SESSION['uid'];


	//Strips out all characters that could be used for malicious reasons
	$escapedInputReasoning = trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 !@#$^&*().]/', '', urldecode(html_entity_decode(strip_tags($inputReasoning))))));

	/*$sql= "INSERT INTO HR_DATA (date_of_shift, emp_name, shift_start_time, start_am_pm, shift_end_time, end_am_pm, reasoning, datetime_shift_given_up, uid)
	VALUES
	('$date','$inputName','$inputStart','$inputStartAMPM', '$inputEnd', '$inputEndAMPM','$escapedInputReasoning', now(), '$uid')";*/

	mysqli_query($con, "INSERT INTO HR_DATA (date_of_shift, emp_name, shift_start_time, start_am_pm, shift_end_time, end_am_pm, reasoning, datetime_shift_given_up, uid) VALUES('$date','$inputName','$inputStart','$inputStartAMPM', '$inputEnd', '$inputEndAMPM','$escapedInputReasoning', now(), '$uid')");

	$fID = mysqli_insert_id($con);

	/*if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}*/


	//Update employee stats
	$sql = "INSERT INTO EMP_STATS(emp_name, num_shifts_given) VALUES ('$inputName', '1') ON DUPLICATE key UPDATE num_shifts_given = num_shifts_given + 1 ";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}

	//Adding to points hours_coverer
	$difference = getHourDiff($inputStart, $inputStartAMPM, $inputEnd, $inputEndAMPM);

	$sql = "INSERT INTO EMP_STATS(emp_name, hours_covered) VALUES('$inputName', $difference) ON DUPLICATE key UPDATE hours_covered = hours_covered - $difference";
	if (!mysqli_query($con,$sql))
	{
		die('Error: ' . mysqli_error($con));
	}



	mysqli_close($con);

///////////////////
//AUTO-SEND EMAIL//
///////////////////


//error_reporting(E_STRICT);
date_default_timezone_set('America/Los_Angeles');
require_once('/usr/share/php/libphp-phpmailer/class.phpmailer.php');

//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();


$body             = "<html>A shift has been put up for grabs by: <b>" . $covName . "</b>!</br></br><b>Date: </b>" . $date . " (". $dayOfWeek .")</br><b>Shift Start: </b>" . $inputStart. " " . $inputStartAMPM . "
	</br><b>Shift End: </b>" . $inputEnd . " " . $inputEndAMPM .
	"</br><b>Reason: </b>" . $inputReasoning .
	"</br></br><a href='http://wwustc.com/stcHourManager/confirmation.php?id=" . $fID . "'> Claim This Shift </a>" .
	"</br></br><i>Thank you for covering shifts!</i></br>--STC</html>";

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mail.yourdomain.com"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = "stchours@gmail.com";  // GMAIL username
$mail->Password   = "<PASSWORD HERE>";            // GMAIL password
$mail->SetFrom("stchours@gmail.com", "STC hours");

//$mail->AddReplyTo("no-reply@yourdomain.com","First Last");
$mail->Subject    = "New Shift up for grabs";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->isHTML(true);
$mail->Body = $body;

$address = "grp.its.ATUS.STC@wwu.edu";
//$address = "vukovis@wwu.edu";
$mail->AddAddress($address, "STC");

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
}else {
	echo "<marquee><span style=\"font-size:20px;font-weight:bold;\">Message sent!</span></marquee>";
}


echo "<h1 align=\"center\">Thank you. Your request has been received.</h1>\n <p align=\"center\">Please check that your hours appear on the main page.</p>";
header( "refresh:3;url=index.php");
exit;


?>
