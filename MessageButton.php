<?php
/*
  MessageButton.php

  Author: Bryan Holdt
  Created: October 2014
  Modified: December 8, 2014

  This page consists of a form that the user can fill out
  and their message will be sent to the entire STC group.

  Note: This page doesn't do the mailing. It is merely
  an interface for the php script.

*/

  session_start();
  
  if(!(isset($_SESSION['uid']))){
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=http://west.wwu.edu/stcsp/stc000/CAS/hours.asp">';    
    exit;
  }
?>

<html>
<head>

  <title>Message Group -- STC</title>
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
            <li class="active"><a href="#">Message Group</a></li>
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

   <div id="cont" class="animated bounceInUp col-sm-12" style="margin-top:90px;">
     <form action="mailer.php" method="post">
      <div class="control-group">
       <label class="control-label" for="inputMessages">Email Message to grp.atus.its.STC</label>
       <div class="controls">
        <p>Name:</p>
        <input type="text" name="name" />
        <p>E-mail:</p>
        <input type="text" name="email" /></br>
        <p>Subject:</p>
        <input type="text" name="subject" /></br>
        <p>Message:</p>
        <textarea name="inputMessages" id="inputMessages" cols="80" rows="8" required></textarea>                           
      </div>
    </div>
    <input type="submit" name="submit" id="submit" value="Send Email" class="btn btn-warning btn-lg" style="margin-top:20px;margin-left:70px;" />
  </form>
	</div>
</div> 
</body>
</html>
