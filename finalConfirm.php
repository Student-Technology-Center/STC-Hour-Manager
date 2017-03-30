<?php
  /*
      finalConfirm.php

      Author: Bryan Holdt

      This page is responsible for confirming that a user wants to cover an entire shift.
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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<script>
    /*Validate the form and prevent submission if the shift ends before it starts or vice versa */
    function TimeCheck(){
      var input = document.getElementById('txtDate').value;
      var parts = input.split("-");
      var target = new Date(parts[0], parts[1]-1, parts[2]); 
      var now = new Date();
      if(now > target){
        alert("You cannot give up a shift on a date that has already passed.");
        return false; 
      }

      //Get values of AM/PM selection
      var SaOp = document.getElementById('desiredStartAMPMdropdown').value;
      var EaOp = document.getElementById('desiredEndAMPMdropdown').value;
      //Get values of Shift start and end times
      var s = document.getElementById('inputDesiredStart').value;
      var e = document.getElementById('inputDesiredEnd').value;
      //Isolate the hour of the time
      var newS = s.substring(0, s.indexOf(':'));
      var newE = e.substring(0, e.indexOf(':'));

       if(newS > 12 || newE > 12){
        alert("Incorrect Time format. Please use 12 hour format.")
        return false;
      }

      var nS = parseInt(newS);
      var nE = parseInt(newE);
 
      //Convert to 24 hour format for just the hour
      if(SaOp != "AM"){
         nS = nS + 12;
      }
      if(EaOp != "AM"){
        nE = nE + 12;
      }
      //Check valid hour
      if(nS > 24 || nE > 24){
        alert("Incorrect Time format. Please fix and try again.")
        return false;
      }

       //Check that format is 8:00 and not 08:00
      if(nS.charAt(0) == 0){ //08:00 , ie. contains a leading zero, will not work
        alert("Incorrect Time format. Please remove all leading 0's (zero's)");
        return false;
      }
      if(nE.charAt(0) == 0){
        alert("Incorrect Time format. Please remove all leading 0's (zero's)");
        return false;
      }

      //Check if end is before the start
      if(nS > nE){
        alert("Shift ends before shift starts! Please fix and try again.");
        return false;
      }else if(nS == nE){
        alert("Shift cannot start and end at the same time. Please fix and try again.");
        return false;
      }else{
        return true;
      }
      return true;
    }
</script>


 <script>
  $(function() {
    var empNames = [
      "Bryan Holdt",
      "AJ Butler",
      "Brendan Baalke",
      "Evan Sirchuk",
      "Carina Linder Jimenez",
      "Chris Scott",
      "Christopher Utterback",
	  "Cooper Lamb",
	  "Dane Lindell",
      "Dominic Lusk",
	  "Franchine Ninh",
      "Jason Barrett",
	  "Joelle Lo",
      "Jordan Kubichek",
      "Josiah Ubben",
	  "Kaleb Hebert",
      "Kariana Aldrich",
      "Kathy Hodges",
      "Katy Bentz",
      "Kayla Adams",
      "Lauren Hammer",
	  "Maddy Ruppel",
	  "Maddie Dougherty",
	  "Mikayla Nicholson",
      "Paul Weidner",
      "Rae Hemmen",
      "Sasa Vukovic",
      "Shawna Zusi-Cobb",
      "Skylar Aieta",
      "Spencer Bui",
      "Thayne Yazzie",
      "Wesley Boyett",
	  "William Felix"
    ];
    $( "#covName" ).autocomplete({
      source: empNames
    });
  });
  </script>
  
</head>
<body>
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
          <span class="navbar-brand">STC Hours</span>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Cover Hours</a></li>
            <li><a href="give.html">Give Hours</a></li>
            <li><a href="MessageButton.html">Message Group</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
             <li><a href="fileTicket.php">File Ticket</a></li>
            <li class="active"><a href="activeTickets.php">Archived Tickets</a></li>
            <li class="active"><a href="commentGenerator.php">Generate Comments</a></li>
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

            echo "<b>Original shift: </b></br>";
            
            echo $sDate->format('M jS') . ', ' . $sDate->format('Y') . ' - ' . $sDate->format('l');
            echo "</br>";
            echo "<div id=\"dst\">";
            echo "<b>Start time: </b>" . $startTime->format('G:i') . ' ' . $row['start_am_pm'];
            echo "</br><b>End time: </b>" . $endTime->format('G:i') . ' ' . $row['end_am_pm'];
            echo "</br>";
          }
          echo "</br>";  
          echo "<b>You agree to cover: </b>";
          echo "</br>";

          echo "<form class=\"form-horizontal\" action=\"partialInsert.php\" method=\"post\" name=\"partialHoursFrm\" id=\"partialHoursFrm\" role=\"form\" onsubmit=\"return TimeCheck()\">
                                        
                      <div class=\"control-group\">
                        <label class=\"control-label\" for=\"inputDesiredStart\">Start Shift</label>
                        <div class=\"controls\">
                          <input type=\"text\" id=\"inputDesiredStart\" name=\"inputDesiredStart\" placeholder=\"8:00\" size=\"10\" required>
                          <select name=\"desiredStartAMPMdropdown\" id=\"desiredStartAMPMdropdown\" class=\"ampmWidth\"> 
                          <option value=\"AM\">AM</option>
                          <option value=\"PM\">PM</option>
                  </select>
                     </div>
                     </div>

                      <div class=\"control-group\">
                        <label class=\"control-label\" for=\"inputEnd\">End Shift</label>
                        <div class=\"controls\">
                         
                            <input type=\"text\" id=\"inputDesiredEnd\" name=\"inputDesiredEnd\" placeholder=\"8:00\" size=\"10\" required>
                            <select name=\"desiredEndAMPMdropdown\" id=\"desiredEndAMPMdropdown\" class=\"ampmWidth\"> 
                              <option value=\"AM\">AM</option>
                              <option value=\"PM\">PM</option>
                            </select>                               
                        </div>
                      </div>

                      </br></br> 

                      <input type=\"hidden\" id=\"datID\" name=\"datID\" value=\"$dID\"> 
                      <input type=\"text\" id=\"covName\" name=\"covName\" placeholder=\"First Last\" size=\"25\" required>    </br>        
                     
                      <input type=\"submit\" name=\"submit\" id=\"submit\" value=\"Claim part of shift!\" class=\"btn btn-warning btn-lg\" style=\"margin-top:20px;margin-left:0px;\" />
                   </form>";
          
          echo "</br>";
        ?>
        </div><!-- End content -->
      </div><!--End main -->
    </div><!--End container-->
  </body>
  </html>
