<?php
/*
    give.php

    Author: Bryan Holdt
    Date Created: September 2014

    This page is responsible for allowing a user to give up their shift.
    This page consists of a form that the user will fill out.

    The data is sent to insert.php
*/
  session_start();

  if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';
    exit;
  }
?>

<html>
<head>

  <title>Give up hours -- STC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/ico" href="images/favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
 <!-- <link type="text/css" rel="stylesheet" href="http://ajax.microsoft.com/ajax/jquery.ui/1.8.6/themes/smoothness/jquery-ui.css" />
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> -->
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
  $(function() {
   $( "#txtDate" ).datepicker({
     dateFormat: 'yy-mm-dd'
   });
 });
  </script>

   
   <script>
  $(function() {
    var empNames = [
      "Jason Barrett",
      "Sasa Vukovic",
      "Alexis Ayala",
      "Carter Dojan",
      "Christian Brintnall",
      "Cimaje Horace",
      "Courtney Scott",
      "Eric Slyman",
      "Evan Carlsen",
      "Franchine Ninh",
      "Gideon Wolfe",
      "Jagannath Natarajan",
      "Joelle Lo",
      "Jordan Kubichek",
      "Kaleb Hebert",
      "Maddie Dougherty",
      "Maddy Ruppel",
      "Mikayla Nicholson",
      "Paul Weidner",
      "Raeanne Hemmen",
      "Shaylee Danielson",
      "Skylar Aieta"
    ];
    $( "#inputName" ).autocomplete({
      source: empNames
    });
  });
  </script>


  <script>

  /*Validate the form and prevent submission if the shift ends before it starts or vice versa */
  function frmCheck(){
    var nameInput = document.getElementById('inputName').value;
    var namePrts = nameInput.split(" ");
    console.log(namePrts.length);
    if(namePrts.length < 2){
      alert("Please enter your first and last name.");
      return false;
    }

    var input = document.getElementById('txtDate').value;
    var parts = input.split("-");
    var target = new Date(parts[0], parts[1]-1, parts[2]);
    var now = new Date();
    if(now.setHours(0,0,0,0) > target.setHours(0,0,0,0)){
      alert("You cannot give up a shift on a date that has already passed.");
      return false;
    }

      //Get values of AM/PM selection
      var SaOp = document.getElementById('StartAMPMdropdown').value;
      var EaOp = document.getElementById('EndAMPMdropdown').value;
      //Get values of Shift start and end times
      var s = document.getElementById('inputStart').value;
      var e = document.getElementById('inputEnd').value;
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
      if(SaOp != "AM" && nS != 12){
       nS = nS + 12;
     }
     if(EaOp != "AM" && nE != 12){
      nE = nE + 12;
    }
      //Check valid hour
      if(nS > 24 || nE > 24){
        alert("Incorrect Time format. Please fix and try again.")
        return false;
      }

      //Check that format is 8:00 and not 08:00
      if(newS.charAt(0) == "0"){ //08:00 , ie. contains a leading zero, will not work
        alert("Incorrect Time format. Please remove all leading 0's (zero's)");
        return false;
      }
      if(newE.charAt(0) == "0"){
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
            <li class="active"><a href="#">Give Hours</a></li>
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

    <div id="content" class="animated bounceInUp col-sm-12" style="background-color: transparent;margin-top:65px;">
     <div class="row">
      <form class="form-horizontal" action="insert.php" method="post" name="giveHoursFrm" id="giveHoursFrm" role="form" onsubmit="return frmCheck()">
       <div class="control-group">
        <label class="control-label" for="inputName">First Last</label>
        <div class="controls">
          <input type="text" id="inputName" name="inputName" placeholder="First Last" size="35" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="txtDate">Date</label>
        <div class="controls">
          <input type="text" id="txtDate" name="txtDate" placeholder="yyyy-mm-dd" size="35" autocomplete="off" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="inputStart">Start Shift</label>
        <div class="controls">
          <input type="text" id="inputStart" name="inputStart" placeholder="8:00" size="15" required>
          <select name="StartAMPMdropdown" id="StartAMPMdropdown" class="ampmWidth">
            <option value="AM">AM</option>
            <option value="PM">PM</option>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputEnd">End Shift</label>
        <div class="controls">

          <input type="text" id="inputEnd" name="inputEnd" placeholder="9:00" size="15" required>
          <select name="EndAMPMdropdown" id="EndAMPMdropdown" class="ampmWidth">
            <option value="AM">AM</option>
            <option value="PM">PM</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="inputReasoning">Reasoning</label>
        <div class="controls">
          <textarea name="inputReasoning" id="inputReasoning" cols="35" rows="" required></textarea>
        </div>
      </div>
      <input type="submit" name="submit" id="submit" value="Give Up Hours" class="btn btn-warning btn-lg" style="margin-top:20px;margin-left:70px;" />
    </form>
  </div><!-- End row -->
</div><!-- End content -->
</div><!--End main -->
</div>
</body>
</html>
