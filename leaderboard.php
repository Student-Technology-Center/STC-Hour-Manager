<?php
/*
  leaderboard.php

  Author: Bryan Holdt
  Date Created: October 2014

  This page is responsible for 
*/
  session_start();

  if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';    
    exit;
  }
?>

<html>
<head>
	<title>Leaderboard -- STC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/ico" href="images/favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
  <link rel="stylesheet" href="css/style.css">
  <link type="text/css" rel="Stylesheet" href="http://ajax.microsoft.com/ajax/jquery.ui/1.8.6/themes/smoothness/jquery-ui.css" />
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">

   <div id="main" class="row">
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
            <li><a href="index.php">Cover Hours</a></li>
            <li><a href="give.php">Give Hours</a></li>
            <li><a href="MessageButton.php">Message Group</a></li>
            <li class="active"><a href="#">Leaderboard</a></li>
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

    <div id="content" class="animated bounceInUp col-sm-8" style="background-color: transparent; margin-top:60px;">
      <table id="hourTable" class="table table-striped text-center">
       <thead>
        <tr>
          <th class="text-center">Name</th>
          <th class="text-center">Shifts Covered</th>
          <th class="text-center">Shifts Given</th>
          <th class="text-center">Shift +/-</th>
          <th class="text-center">Hours Covered +/-</th>
        </tr>
      </thead>
      <tbody>
       <?php
       $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());

       mysql_select_db("hourTracker", $link) or die(mysql_error());

          /*
            Longest Wait time
          
          $result = mysql_query("SELECT * FROM HR_DATA ORDER BY datetime_shift_given_up ASC LIMIT 1", $link) or die(mysql_error());
          $num_results = mysql_num_rows($result); 

          if($num_results > 0){
            while($row = mysql_fetch_array($result)){
              $person = $row['emp_name'];
              $givenTime =  $row['datetime_shift_given_up'];
            }

            $query = mysql_query("SELECT TIMESTAMPDIFF(HOUR,'$givenTime', NOW())", $link) or die(mysql_error());
            $row = mysql_fetch_row($query);
            $waitTime = $row[0];

              echo "<div class=\"panel panel-primary\">
                <div class=\"panel-heading\">
                <h5 class=\"panel-title\">Longest Wait Time: </h5>
                </div>
                <div class=\"panel-body\">" .
                $person . " - <b>" . $waitTime . " hours</b>" .
                "</div>
                </div>";
              }else{
                echo "<div class=\"panel panel-primary \">
                <div class=\"panel-heading\">
                <h5 class=\"panel-title\">Longest Wait Time: </h5>
                </div>
                <div class=\"panel-body\">" .
                "No one is currently waiting!" .
                "</div>
                </div>";
              }
          mysql_close($link);
        /*
         END Longest Wait time
       */


         /* 
          MOST SHIFTS Given
         
         $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
          //select database
          mysql_select_db("hourTracker", $link) or die(mysql_error());
          
          $result = mysql_query("SELECT * FROM EMP_STATS ORDER BY num_shifts_given DESC LIMIT 1", $link) or die(mysql_error());
          $num_results = mysql_num_rows($result); 

          if($num_results > 0){
            while($row = mysql_fetch_array($result)){
              $person = $row['emp_name'];
              $given =  $row['num_shifts_given'];
            }

              echo "<div class=\"panel panel-primary \">
                <div class=\"panel-heading\">
                <h5 class=\"panel-title\">Most shifts given: </h5>
                </div>
                <div class=\"panel-body\">" .
                $person . " - <b>" . $given . " shifts</b>" .
                "</div>
                </div>";
              }else{
                echo "<div class=\"panel panel-primary \">
                <div class=\"panel-heading\">
                <h5 class=\"panel-title\">Most shifts given: </h5>
                </div>
                <div class=\"panel-body\">" .
                "No one has covered shifts yet!" .
                "</div>
                </div>";
              }
          mysql_close($link);
        /*
          End Most Shifts Given
        */



         /* 
          MOST SHIFTS Covered
         
         $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
          //select database
          mysql_select_db("hourTracker", $link) or die(mysql_error());
         
          $result = mysql_query("SELECT * FROM EMP_STATS ORDER BY num_shifts_covered DESC LIMIT 1", $link) or die(mysql_error());
          $num_results = mysql_num_rows($result); 

          if($num_results > 0){
            while($row = mysql_fetch_array($result)){
              $person = $row['emp_name'];
              $covered =  $row['num_shifts_covered'];
            }

              echo "<div class=\"panel panel-primary \">
                <div class=\"panel-heading\">
                <h5 class=\"panel-title\">Most shifts covered: </h5>
                </div>
                <div class=\"panel-body\">" .
                $person . " - <b>" . $covered . " shifts</b>" .
                "</div>
                </div>";
              }else{
                echo "<div class=\"panel panel-primary \">
                <div class=\"panel-heading\">
                <h5 class=\"panel-title\">Most shifts covered: </h5>
                </div>
                <div class=\"panel-body\">" .
                "No one has given up shifts yet!" .
                "</div>
                </div>";
              }
          mysql_close($link);

        /* 
          END MOST SHIFTS COVERED
         */


          /*
            Display a table with the data for each employee. This is similar to the process done in index.php  
          */

            $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
            //select database
            mysql_select_db("hourTracker", $link) or die(mysql_error());
            
            //Delete database entries that have already passed. Should only happen if a shift never gets claimed.
            //mysql_query("DELETE FROM HR_DATA WHERE date_of_shift <= DATE_ADD(CURDATE(), INTERVAL - 1 DAY);", $link);

            $curDate = date('Y-m-d');
            //$result = mysql_query("SELECT * FROM EMP_STATS ORDER BY hours_covered DESC", $link) or die(mysql_error());
            $result = mysql_query("SELECT * FROM EMP_STATS ORDER BY hours_covered DESC, num_shifts_covered DESC", $link) or die(mysql_error());

            while ($row = mysql_fetch_array($result)){
              $numShiftsCovered = $row['num_shifts_covered'];
              $numShiftsGiven = $row['num_shifts_given'];
              $hoursCovered = $row['hours_covered'];

              /* Print contents of the entry */ 
              echo "<tr>";
              echo "<td>" . $row['emp_name'] . "</td>";
              echo "<td>" . $numShiftsCovered . "</td>";
              echo "<td>" . $numShiftsGiven . "</td>";
              echo "<td>" . ($numShiftsCovered - $numShiftsGiven) . "</td>";
              echo "<td>" . $hoursCovered . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div><!-- End content -->
    </div><!--End main -->
  </div>
</body>
</html>