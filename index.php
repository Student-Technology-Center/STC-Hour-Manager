<?php
/*
  index.php

  Author: Bryan Holdt
  Date Created: September 2014

  This page is the home page for the STC Hour Manager.

  This page is responsible for displaying what shifts still need to be covered.
  A user has the option to cover an available shift from this page.
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  session_start();
?>
<html>
<head>
	<title>STC Hour Manager</title>
	<link rel="icon" type="image/ico" href="images/favicon.ico">
	<link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="konami.js"></script>
	<link rel="stylesheet" href="css/style.css">
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
function load(){
   var x = document.getElementById("hourTable").rows.length;
   document.getElementById("badgeText").innerHTML = (x-1);
}

var easter_egg = new Konami();
easter_egg.code = function() { document.getElementById("spencer").src="Everythingisfine.jpg";}
easter_egg.load();
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
            <li class="active"><a href="index.php">Cover Hours</a></li> <!-- <span id="badgeText" class="badge">0</span> -->
            <li><a href="give.php">Give Hours</a></li>
            <li><a href="MessageButton.php">Message Group</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="fileTicket.php">File Ticket</a></li>
            <li><a href="activeTickets.php">Active Tickets</a></li>
            <li><a href="commentGenerator.php">Generate Comments</a></li>
            <li><a href="history.php">Shift History</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout_destroy.php">Logout</a></li>
          </ul> 
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <img style="position: relative; right: 400px;" src=null id="spencer">
		<div id="content" class="animated bounceInUp col-sm-12 text-center" style="background-color: transparent;margin-top:35px;margin-left:-20px;">
      <!--<div><h4>Up for grabs:</h4></div>-->
			<table id="hourTable" class="table table-striped text-center">
      		 <thead>
       			<tr>
         		 	  <th class="text-center">Date</th>
          			<th class="text-center">Name</th>
          			<th class="text-center">Shift Start</th>
          			<th class="text-center">Shift End</th>
          			<th class="text-center">Claim</th>
                <th class="text-center">Delete</th>
       			</tr>
      		 </thead>
      		 <tbody>
       	   <?php
            /*
              Redirect user to the CAS system, if there is no uid parameter in the URL.
            */
            if (!isset($_SESSION["uid"])){
              $identity = $_POST["uid"];
              $secret = $_POST["password"];
              $secret2 = "<PASSWORD HERE>";
              if(is_null($identity) || !strcmp($secret, $secret2)==0){
                //header('Location: http://west.wwu.edu/stcsp/stc000/CAS/hours.asp');
                header("Location: authenticate.php");
              }
              else{
                $_SESSION["uid"]=$identity;
              }
            }

              //set up mysqli connection
              $link = mysqli_connect("localhost", "root", "<PASSWORD HERE>") or die(mysqli_error());
              //select database
              mysqli_select_db($link, "hourTracker") or die(mysqli_error());
              
              //Delete database entries that have already passed. Should only happen if a shift never gets claimed.
              //mysqli_query("DELETE FROM HR_DATA WHERE date_of_shift <= DATE_ADD(CURDATE(), INTERVAL - 1 DAY);", $link);
           
              $curDate = date('Y-m-d');
              $result = mysqli_query($link, "SELECT * FROM HR_DATA WHERE date_of_shift >= CURDATE() ORDER BY date_of_shift") or die(mysqli_error());
              //$badgeNum = mysqli_query("SELECT * FROM HR_DATA", $link) or die(mysqli_error());
              $num_rows = mysqli_num_rows($result);

              echo "<script type='text/javascript'> window.onload=load; </script>";
              if($num_rows == 0){
                echo "<b>" . "Nothing, please check back soon!" . "</b";
              }

              while ($row = mysqli_fetch_array($result)){
                $startTime = date_create($row['shift_start_time']);
                $endTime = date_create($row['shift_end_time']);
                $sDate = date_create($row['date_of_shift']);
                $fID = $row['id'];
                // Print out the contents of the entry 
                echo "<tr>";
                echo "<td>" . $sDate->format('M jS') . ', ' . $sDate->format('Y') . ' - ' . $sDate->format('l') . "</td>";
                echo "<td>" . $row['emp_name'] . "</td>";
                echo "<td>" . $startTime->format('G:i') . ' ' . $row['start_am_pm'] . "</td>";
                echo "<td>" . $endTime->format('G:i') . ' ' . $row['end_am_pm'] . "</td>";
                echo "<td>" . "<button type=\"button\" value=\"" . $row['id'] . "\" class=\"btn btn-success\" onClick=\"javascript:location.href='confirmation.php?id=$fID';\" >Claim!</button> </td>";
                if ($_SESSION['uid']==$row['uid']){
                  echo "<td>" . "<button type=\"button\" value=\"" . $row['id'] . "\" class=\"btn btn-success\" onClick=\"javascript:location.href='drop.php?id=$fID';\" >Delete!</button> </td>";
                }
                else{
                  echo "<td> </td>";
                }
                echo "</tr>";
              }
            ?>      
      		 </tbody>
    		</table>
		</div><!-- End content -->
	</div><!--End main -->
</div><!--End container-->
</body>
</html>
