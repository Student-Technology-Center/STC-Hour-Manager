<?php
/*
    login.php

    Author: Sasa Vukovic
    Date Created: April 2016

*/
  session_start();
?>

<html>
<head>

  <title>Login -- STC</title>
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
  <script type="text/javascript"></script>
  

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
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div id="content" class="animated bounceInUp col-sm-12" style="background-color: transparent;margin-top:65px;">
     <div class="row">
      <form class="form-horizontal" action="insert.php" method="post" name="giveHoursFrm" id="giveHoursFrm" role="form" onsubmit="return frmCheck()">
       
       <div class="control-group">
        <label class="control-label" for="inputName">Universal ID</label>
        <div class="controls">
          <input type="text" id="uid" name="uid" placeholder="smithj4" size="35" required>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label" for="inputName">Password</label>
        <div class="controls">
          <input type="text" id="password" name="password" size="35" required>
        </div>
      </div>


      <input type="submit" name="submit" id="submit" value="Give Up Hours" class="btn btn-warning btn-lg" style="margin-top:20px;margin-left:70px;" />

      <a href="register.php"> Register </a>
    </form>
  </div><!-- End row -->
</div><!-- End content -->
</div><!--End main -->
</div>
</body>
</html>
