<?php
/*
  notcovered.php

  Author: Sasa Vukovic
  Date Created: June 2015

  Displays shifts not covered.
*/
  if(isset($_SESSION['uid'])){
    //Already have a session
  }else{
    session_start();
  }

  if (isset($_POST['startDate'])){
    $startDate = $_POST['startDate'];
    $startDate = strtotime($startDate);
    $startDate = date('Y-m-d',$startDate);
    $startDate = mysql_escape_string($startDate);
  }
  else {
    $startDate = date('Y-m-d', strtotime('-30 days'));
  }

  if (isset($_POST['endDate'])){
    $endDate = $_POST['endDate'];
    $endDate = strtotime($endDate);
    $endDate = date('Y-m-d',$endDate);
    $endDate = mysql_escape_string($endDate);

  }
  else {
    $endDate = date('Y-m-d');
  }


?>

<html>
<head>
	<title>STC Hour Manager</title>
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

		<div id="content" class="animated bounceInUp col-sm-12 text-center" style="background-color: transparent;margin-top:35px;margin-left:-20px;">
      <!--<div><h4>Up for grabs:</h4></div>-->
      <form method = "post">
        <h4 style="display: inline; float: left; clear: right; margin-right: 10px;"> Filter: </h4> 
        <input style="float: left; position: relative; top: 5px;" type="text" id="search" name="search" value="<?php echo $_POST['search']?>"></input>
        <h4 style="display: inline; float: left; margin-left: 20px; margin-right: 10px;"> Start Date: </h4> 
        <input style="float: left; position: relative; top: 5px;" type="date" name="startDate" value="<?php echo $startDate?>"></input> 
        <h4 style="display: inline; float: left; margin-left: 20px; margin-right: 10px;"> End Date: </h4> 
        <input style="float: left; position: relative; top: 5px;" type="date" name="endDate" value="<?php echo $endDate?>"></input> 
        <input style="float: left; position: relative; top: 5px;" type="submit"></input> 
      </form>
			<table id="hourTable" class="table table-striped text-center">
      		 <thead>
       			<tr>
         		 	  <th class="text-center">Date</th>
          			<th class="text-center">Employee</th>
          			<th class="text-center">Shift Start</th>
          			<th class="text-center">Shift End</th>
                <th class="text-center">Reason </th>
       			</tr>
      		 </thead>
      		 <tbody>
       	   <?php
            /*
              Redirect user to the CAS system, if there is no uid parameter in the URL.
            */


            
              //set up mysql connection
              $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
              //select database
              mysql_select_db("hourTracker", $link) or die(mysql_error());
              
           
              $curDate = date('Y-m-d');


              $result = mysql_query("SELECT * FROM HR_DATA WHERE date_of_shift <= '$endDate' and date_of_shift >= '$startDate' ORDER BY emp_name ASC, date_of_shift DESC ", $link) or die(mysql_error());
              //$badgeNum = mysql_query("SELECT * FROM HR_DATA", $link) or die(mysql_error());
              $num_rows = mysql_num_rows($result);

              echo "<script type='text/javascript'> window.onload=load; </script>";
              if($num_rows == 0){
                echo "<b>" . "Nothing, please check back soon!" . "</b";
              }

              while ($row = mysql_fetch_array($result)){
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
                echo "<td>" . $row['reasoning'] . "</td>";

                echo "</tr>";
              }
            ?>      
      		 </tbody>
    		</table>

        <!--Script to filter table with search bar-->
        <script>
          var $rows = $('#hourTable tbody tr');
          $('#search').keyup(function () {
              var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

              $rows.show().filter(function () {
                  var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                  return !~text.indexOf(val);
              }).hide();
          });
        </script>
		</div><!-- End content -->
	</div><!--End main -->
</div><!--End container-->
</body>
</html>
