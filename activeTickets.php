<?php

/*
  activeTickets.php

  Author: Bryan Holdt
  Created: October 30, 2014

  This file is responsible for displaying all the active tickets.
  It does so by displaying all entries that are in the HARDWARE_TICKETS table.
  This is very similar to the index page.

*/
  session_start();

  if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';    
    exit;
  }
?>
<html>
<head>
	<title>Active Tickets -- STC</title>
	<link rel="icon" type="image/ico" href="images/favicon.ico">
	<link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
function load(){
   var x = document.getElementById("hourTable").rows.length;
   document.getElementById("badgeText").innerHTML = (x-1);
}
</script>

<script>
function claimClick(obj){
  var num = obj.value;
  console.log(num);
  return num;
}
</script>

</head>
<body onload="load">
<div class="container">
	<!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
           <!--<a class="navbar-brand" href="#">STC Hours</a> -->
          <span class="navbar-brand">
            <?php
            if(isset($_SESSION['uid'])){
              echo $_SESSION['uid'];
            }else{
              echo "STC Hours";
            } ?>
          </span>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Cover Hours</a></li> <!-- <span id="badgeText" class="badge">0</span> -->
            <li><a href="give.php">Give Hours</a></li>
            <li><a href="MessageButton.php">Message Group</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="fileTicket.php">File Ticket</a></li>
            <li class="active"><a href="activeTickets.php">Active Tickets</a></li>
            <li><a href="commentGenerator.php">Generate Comments</a></li>
            <li><a href="history.php">Shift History</a></li>

          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout_destroy.php">Logout</a></li>
          </ul> 
        </div><!--/.nav-collapse -->
      </div>
    </div>

		<div id="content" class="animated bounceInUp col-sm-12 text-center" style="background-color: transparent;margin-top:35px;margin-left:-20px;">
      <!--<div><h4>Up for grabs:</h4></div>-->
			<table id="hourTable" class="table table-striped text-center">
      		 <thead>
       			<tr>
         		 	  <th class="text-center">Date Reported</th>
          			<th class="text-center">Reporter</th>
          			<th class="text-center">Location</th>
          			<th class="text-center">Device</th>
          			<th class="text-center">Issue</th>
                <th class="text-center">Resolve</th>
       			</tr>
      		 </thead>
      		 <tbody>
       	   <?php
              
              //set up mysql connection
              $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
              //select database
              mysql_select_db("hourTracker", $link) or die(mysql_error());
              
              //Delete database entries that have already passed. Should only happen if a shift never gets claimed.
              //mysql_query("DELETE FROM HR_DATA WHERE date_of_shift <= DATE_ADD(CURDATE(), INTERVAL - 1 DAY);", $link);
           
              $curDate = date('Y-m-d');
              $result = mysql_query("SELECT * FROM HARDWARE_TICKETS ORDER BY date_reported", $link) or die(mysql_error());
              $num_rows = mysql_num_rows($result);

              echo "<script type='text/javascript'> window.onload=load; </script>";
              if($num_rows == 0){
                echo "<b>" . "Wow, nothing is broken..." . "</b";
              }

              while ($row = mysql_fetch_array($result)){
                $date_reported = date_create($row['date_reported']);
                $reporter = $row['person_who_filed'];
                $location = $row['location'];
                $device = $row['device'];
                $issue = $row['issue'];
                $fID = $row['id'];
                // Print out the contents of the entry 
                echo "<tr>";
                echo "<td>" . $date_reported->format('M jS') . ', ' . $date_reported->format('Y') . ' - ' . $date_reported->format('l') . "</td>";
                echo "<td>" . $reporter . "</td>";
                echo "<td>" . $location . "</td>";
                echo "<td>" . $device . "</td>";
                echo "<td>" . $issue . "</td>";
                echo "<td>" . "<button type=\"button\" value=\"" . $row['id'] . "\" class=\"btn btn-danger\" onClick=\"javascript:location.href='archiveTicket.php?id=$fID';\" >Resolved!</button> </td>";
                echo "</tr>";
              }
            ?>      
      		 </tbody>
    		</table>
        <a href="archivedTicketList.php">View Archived Tickets</a>
		</div><!-- End content -->
	</div><!--End main -->
</div><!--End container-->
</body>
</html>
