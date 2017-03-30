<?php
/*
	delete.php

	This file is responsible for two things: 1) Sending out an email to the group
	when a shift is claimed, and 2) Deleting the proper entry from the database.

	The code is very messy and needs to be cleaned up. If I have time, I will
	try to clean it up a bit.

	-Bryan
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
	$covName = $_POST["covName"];
	echo "Beginning delete process....</br>";

	$idToDelete = $_GET["id"];
	$varQuery = "SELECT * FROM HR_DATA WHERE id=$idToDelete";
	$link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
	mysql_select_db("hourTracker", $link) or die(mysql_error());
	$result = mysql_query($varQuery, $link) or die(mysql_error());


	while ($row = mysql_fetch_object($result)) {
		$empGiving = $row->emp_name;
		$DOS = $row->date_of_shift;
		$SST = $row->shift_start_time;
		$SET = $row->shift_end_time;
		$Startampm = $row->start_am_pm;
		$Endampm = $row->end_am_pm;
		$removeduid = $row->uid;
	}

	echo "Executing SQL...</br>";

	$newSST = date('h:i', strtotime($SST));
	$newSET = date('h:i', strtotime($SET));

	$archiveSQL = "INSERT INTO SHIFT_ARCHIVE(date_of_shift, emp_giving, emp_covering, shift_start_time, shift_end_time, start_am_pm, end_am_pm)
					VALUES('$DOS', '$empGiving', '$covName','$SST', '$SET', '$Startampm', '$Endampm')";

	mysql_query($archiveSQL, $link) or die(mysql_error());

	$sql = "INSERT INTO EMP_STATS(emp_name, num_shifts_covered) VALUES ('$covName', '1') ON DUPLICATE key UPDATE num_shifts_covered = num_shifts_covered + 1 ";
	mysql_query($sql, $link) or die(mysql_error());

	/* Update EMP_STATS */
	$difference = getHourDiff($SST, $Startampm, $SET, $Endampm);
	$sql = "INSERT INTO EMP_STATS(emp_name, hours_covered) VALUES('$covName', '$difference') ON DUPLICATE key UPDATE hours_covered = hours_covered + '$difference'";
	mysql_query($sql, $link) or die(mysql_error());
	
	mysql_close($link);

	echo "SQL executed.</br>";

	error_reporting(E_STRICT);
	date_default_timezone_set('America/Los_Angeles');
	require_once('/usr/share/php/libphp-phpmailer/class.phpmailer.php');
	//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

	$mail             = new PHPMailer();
	$body             = "Your shift has been covered!</br></br><b>Date: </b>" . $DOS . 
	"</br><b>Shift Start: </b>" . $newSST . " " . $Startampm . "
	</br><b>Shift End: </b>" . $newSET . " " . $Endampm . "

	</br></br><i>You should thank <b>" . $covName . "</b> for covering your shift!</i></br>
	--STC";
	
$mail->IsSMTP(); 						   // telling the class to use SMTP
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

$mail->Subject    = "Shift Covered";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);
$address = $removeduid."@wwu.edu";
$mail->AddAddress($address, $removeduid);
$mail->AddAddress("aldrick3@wwu.edu", "Kariana");

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "<marquee><span style=\"font-size:20px;font-weight:bold;\">Hours Deleted!</span></marquee>";
}


$con = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
mysql_select_db("hourTracker", $con) or die(mysql_error());

/*Delete from db */
$query2 = "DELETE FROM HR_DATA WHERE id=$idToDelete";

if (!mysql_query($query2,$con))
{
	die('Error: ' . mysql_error($con));
}
mysql_close($con);

header( "refresh:3;url=index.php");

?>