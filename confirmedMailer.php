<?php
/*
	confirmedMailer.php

	Author: Bryan Holdt
	Created: September 2014

	TO:DO -- Write up
*/

echo "<marquee direction=\"right\">Initializing...</marquee></br>";

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Los_Angeles');

require_once('../../../usr/share/php/libphp-phpmailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();



$body             = $_POST['inputMessages'];


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

$mail->SetFrom($_POST['email'], $_POST['name']);

$mail->Subject    = $_POST['subject'];

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "techcenter@techcenter@wwu.edu";
$mail->AddAddress($address, "Bryan Holdt");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "<marquee>Message sent!</marquee>";
  echo "</br></br></br><a href=\"MessageButton.html\">Back</a>";
header( "refresh:3;url=http://192.241.201.209/stcHourManager/");
exit;
}
?>