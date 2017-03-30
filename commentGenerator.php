<?php
/*
    commentGenerator.php

    Author: Bryan Holdt
    Date Created: September 2014

    This page is the interface for the commentGenLogic.php page.

    Page consists of a form that asks for a employee name as well
    as the current time sheet date range. Comments are generated 
    and echo'd onto the page so that they may be copied and pasted
    into the timesheet.
*/
  session_start();

  if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';
    exit;
  }
?>

<html>
<head>

  <title>Generate Comments -- STC</title>
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
   $( "#txtDateStart" ).datepicker({
     dateFormat: 'yy-mm-dd'
   });
 });
  </script>
  <script type="text/javascript">
  $(function() {
   $( "#txtDateEnd" ).datepicker({
     dateFormat: 'yy-mm-dd'
   });
 });
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
      "Dominic Lusk",
      "Jason Barrett",
      "Jordan Kubichek",
      "Josiah Ubben",
      "Kariana Aldrich",
      "Kathy Hodges",
      "Katy Bentz",
      "Kayla Adams",
      "Lauren Hammer",
      "Paul Weidner",
      "Rae Hemmen",
      "Sasa Vukovic",
      "Shawna Zusi-Cobb",
      "Skylar Aieta",
      "Spencer Bui",
      "Thayne Yazzie",
      "Wesley Boyett"
    ];
    $( "#commentName" ).autocomplete({
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
            <li><a href="fileTicket.php">File Ticket</a></li>
            <li><a href="activeTickets.php">Active Tickets</a></li>
            <li class="active"><a href="commentGenerator.php">Generate Comments</a></li>
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
      <form class="form-horizontal" action="commentGenLogic.php" method="post" name="commentGenFrm" id="commentGenFrm" role="form">
       <div class="control-group">
        <label class="control-label" for="commentName">First Last</label>
        <div class="controls">
          <input type="text" id="commentName" name="commentName" placeholder="First Last" size="35" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="txtDateStart">Start Date</label>
        <div class="controls">
          <input type="text" id="txtDateStart" name="txtDateStart" placeholder="yyyy-mm-dd" size="35" autocomplete="off" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="txtDateEnd">End Date</label>
        <div class="controls">
          <input type="text" id="txtDateEnd" name="txtDateEnd" placeholder="yyyy-mm-dd" size="35" autocomplete="off" required>
        </div>
      </div>
    
      <input type="submit" name="submit" id="submit" value="Generate Comments" class="btn btn-warning btn-lg" style="margin-top:20px;margin-left:40px;" />
    </form>
  </div><!-- End row -->
</div><!-- End content -->
</div><!--End main -->
</div>
</body>
</html>
