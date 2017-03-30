<?php

/*
  archiveTicket.php

  Author: Bryan Holdt
  Date Created: October 30, 2014

  This file is responsible for two things. 1) Add the issue that was resolved to
  the archived ticket table in the database. This is important so that we can retrieve
  information about previous things that went wrong. 2) Delete the active ticket from the
  HARDWARE_TICKETS table in the database. This ticket is no longer active, so we will remove
  it so the hardware team knows the issue has been resolved. 

*/
  
  /* Start a session and redirect if not logged in. */
session_start();
if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';
    exit;
 }


  /* Archive the data  */
  echo "Beginning archive process...</br>";

  $idToArchive = $_GET["id"];
  $resolvedBy = $_SESSION['uid'];

  $sql = "SELECT * FROM HARDWARE_TICKETS WHERE id=$idToArchive";
  $link = mysqli_connect("localhost", "root", "<PASSWORD HERE>", "hourTracker") or die(mysqli_error());

  $result = mysqli_query($link, $sql) or die(mysqli_error());

  while ($row = mysqli_fetch_array($result)) {
   $archId = $row['id'];
   $archDate = $row['date_reported'];
   $archPerson = trim(preg_replace('/[^A-Za-z0-9  !@#$%^&*().-]/', '', strip_tags($row['person_who_filed'])));
   $archLocation = trim(preg_replace('/[^A-Za-z0-9  !@#$%^&*().-]/', '', strip_tags($row['location'])));
   $archDevice = trim(preg_replace('/[^A-Za-z0-9  !@#$%^&*().-]/', '', strip_tags($row['device'])));
   $archIssue = trim(preg_replace('/[^A-Za-z0-9  !@#$%^&*().-]/', '', strip_tags($row['issue'])));
  }
  /* Insert the data into the archive */
  $insSQL = "INSERT INTO HARDWARE_TICKET_ARCHIVE (id, date_reported, person_who_filed, location, device, issue, date_resolved, resolved_by)
  VALUES ('$archId', '$archDate', '$archPerson', '$archLocation', '$archDevice', '$archIssue', now(), '$resolvedBy')";

  mysqli_query($link, $insSQL) or die(mysql_error());
  echo "Archive complete.</br>";

  echo "Removing original ticket from the database...</br>";

  $delSQL = "DELETE FROM HARDWARE_TICKETS WHERE id=$idToArchive";
  mysqli_query($link, $delSQL);
  echo "Original ticket removed.</br>";

  mysqli_close($link);

  header( "refresh:3;url=http://192.241.201.209/stcHourManager/");

?>