<?php
/*
	ticketInsert.php

	Author: Bryan Holdt
	Date: October 23rd, 2014

	This file is responsible for taking the form data from
	fileTicket.php and inserting it into the database.

	This file inserts into the HARDWARE_TICKETS table in the hourTracker db. 
*/

  session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$filerName = $_POST["inputName"];
$location = $_POST["inputLocation"];
$device = $_POST["inputDevice"];
$issue = $_POST["inputIssue"];
$escapedIssue = trim(preg_replace('/[^A-Za-z0-9  !@#$%^&*().-]/', '', strip_tags($issue)));

$link = mysqli_connect("localhost", "root", "<PASSWORD HERE>") or die(mysqli_error($link));
//select database
mysqli_select_db($link, "hourTracker") or die(mysqli_error($link));


$sql = "INSERT INTO HARDWARE_TICKETS (date_reported, person_who_filed, location, device, issue) 
		VALUES (NOW(), '$filerName', '$location', '$device', '$escapedIssue')";

mysqli_query($link, $sql) or die(mysqli_error($link)); 	
mysqli_close($link);


//Email the hardware team
	date_default_timezone_set('America/Los_Angeles');
	require_once('/usr/share/php/libphp-phpmailer/class.phpmailer.php');

//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

	$mail             = new PHPMailer();

	$body             = "Hey hardware team, </br>A new ticket has been filed!</br></br>
						 Issue: " . $issue . 
						 "</br><b>Location:</b> " . $location . 
						 "</br><b>Device: </b>" . $device . 
						 "</br><b>Who filed: </b>" . $filerName . "</br></br>--STC Hour Manager";

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
$mail->SetFrom("stchours@gmail.com", "STC Tickets");

//$mail->AddReplyTo("no-reply@yourdomain.com","First Last");

$mail->Subject    = "New Hardware Ticket Filed";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);
//$address = "Bryan.Holdt@wwu.edu";
$hardwareTeamMembers = array('Paul.Weidner@wwu.edu' => 'Paul Weidner',
							 'Skylar.Aieta@wwu.edu' => 'Skylar Aieta');

foreach($hardwareTeamMembers as $email => $name){
	$mail->AddAddress($email, $name);
}

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
}else {
	echo "<marquee><span style=\"font-size:20px;font-weight:bold;\">Message sent!</span></marquee>";
}


echo "<h1 align=\"center\">Thank you. Your request has been received.</h1>";
header( "refresh:3;url=http://192.241.201.209/stcHourManager/");
exit;
?>