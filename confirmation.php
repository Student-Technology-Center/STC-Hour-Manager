<?php
/*
  confirmation.php

  Author: Bryan Holdt
  Created: September 2014

  This page is responsible for displaying the shift a person is about to cover.

  This page also offers the user the choice of whether to cover an entire shift or just part
  of a shift.
*/

  session_start();
?>
<html>
<head>
	<title>Confirm Hour Grab</title>
	<link rel="icon" type="image/ico" href="images/favicon.ico">
	<link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
	<link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
	<link rel="stylesheet" href="css/style.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
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
            <li class="active"><a href="index.php">Cover Hours</a></li>
            <li><a href="give.php">Give Hours</a></li>
            <li><a href="MessageButton.php">Message Group</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="fileTicket.php">File Ticket</a></li>
            <li><a href="activeTickets.php">Active Tickets</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout_destroy.php">Logout</a></li>
          </ul> 
        </div><!--/.nav-collapse -->
      </div>
    </div>

        <div id="content" class="animated bounceInUp col-sm-6 text-center" style="background-color: transparent;margin-top:45px;">
          <?php
          //set up mysql connection
          $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
          //select database
          mysql_select_db("hourTracker", $link) or die(mysql_error());

          $dID = $_GET["id"];
          $result = mysql_query("SELECT * FROM HR_DATA WHERE id=$dID", $link) or die(mysql_error());
          
          
          while ($row = mysql_fetch_array($result)){
            $startTime = date_create($row['shift_start_time']);
            $endTime = date_create($row['shift_end_time']);
            $sDate = date_create($row['date_of_shift']);
            $fID = $row['id'];

       
            echo "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
            <h5 class=\"panel-title\">Date: </h5>
            </div>
            <div class=\"panel-body\">" .
            $sDate->format('M jS') . ', ' . $sDate->format('Y') . ' - ' . $sDate->format('l') . 
            "</div>
            </div>";

            echo "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
            <h3 class=\"panel-title\">Covering: </h3>
            </div>
            <div class=\"panel-body\">" .
            $row['emp_name'] . 
            "</div>
            </div>"; 

            echo "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
            <h3 class=\"panel-title\">Shift Start: </h3>
            </div>
            <div class=\"panel-body\">" .
            $startTime->format('G:i') . ' ' . $row['start_am_pm'] . 
            "</div>
            </div>";

            echo "<div class=\"panel panel-primary\">
            <div class=\"panel-heading\">
            <h3 class=\"panel-title\">Shift End: </h3>
            </div>
            <div class=\"panel-body\">" .
            $endTime->format('G:i') . ' ' . $row['end_am_pm'] . 
            "</div>
            </div>";                        
          }


          echo "</br>";
          echo "Do you want to claim....";
          echo "</br>";
          //echo "<button type=\"button\" class=\"btn btn-success\" onClick=\"javascript:location.href='delete.php?id=$fID';\" >Entire Shift!</button>";
          echo "<button type=\"button\" class=\"btn btn-success\" onClick=\"javascript:location.href='entireConfirm.php?id=$fID';\" >Entire Shift!</button>";
          echo "\t | \t";
          echo "<button type=\"button\" class=\"btn btn-success\" onClick=\"javascript:location.href='finalConfirm.php?id=$fID';\" >Part of Shift!</button>";
          ?>
        </div><!-- End content -->
      </div><!--End main -->
    </div><!--End container-->
  </body>
  </html>
