<?php
/*
  fileTicket.php

  Author: Bryan Holdt
  Date Created: October 2014

  This page is responsible for inserting tickets into the HARDWARE_TICKETS
  database table. The user will fill out a form and their data will be inserted
  into the table.

  NOTE TO MAINTAINER: This file will automatically send out an email to all
  members of the hardware team.  If you wish to change who is an active hardware team
  member, please see the bottom of the file in the AUTO-SEND EMAIL section. There is an
  array containing names and emails of the current members. Add or remove them from this
  array to change who gets emailed.


*/
  session_start();

  if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';    
    exit;
  }
?>

<html>
<head>

  <title>File Ticket -- STC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/ico" href="images/favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
  <link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
  <link rel="stylesheet" href="css/style.css">
  <link type="text/css" rel="stylesheet" href="http://ajax.microsoft.com/ajax/jquery.ui/1.8.6/themes/smoothness/jquery-ui.css" />
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script>
  function frmCheck(){
    //May be useful in the future.
    //Doesn't check anything at this point in time.
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
            <li><a href="give.php">Give Hours</a></li>
            <li><a href="MessageButton.php">Message Group</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li class="active"><a href="fileTicket.php">File Ticket</a></li>
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
      <form class="form-horizontal" action="ticketInsert.php" method="post" name="fileTicketFrm" id="fileTicketFrm" role="form">
       <div class="control-group">
        <label class="control-label" for="inputName">Your Name</label>
        <div class="controls">
          <input type="text" id="inputName" name="inputName" placeholder="First Last" size="35" required>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="inputLocation">Location</label>
        <div class="controls">
          <input type="text" id="inputLocation" name="inputLocation" placeholder="STC Lab" size="35" required>
        </div>
      </div>
          
      <div class="control-group">
        <label class="control-label" for="inputReasoning">Computer or Device #</label>
        <div class="controls">
          <input type="text" id="inputDevice" name="inputDevice" placeholder="Mac #1" size="35" required>                      
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="inputReasoning">Issue</label>
        <div class="controls">
           <textarea name="inputIssue" id="inputIssue" cols="35" rows="" required></textarea>                 
        </div>
      </div>

      <input type="submit" name="submit" id="submit" value="Submit Ticket" class="btn btn-warning btn-lg" style="margin-top:20px;margin-left:70px;" />
    </form>
  </div><!-- End row -->
</div><!-- End content -->
</div><!--End main -->
</div>
</body>
</html>
