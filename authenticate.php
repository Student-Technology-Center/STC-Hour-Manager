<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
	<title>Temporary Authentication</title>
	<link rel="icon" type="image/ico" href="images/favicon.ico">
	<link rel="stylesheet" href="css/bootstrap.css"><!-- load bootstrap -->
	 <link rel="stylesheet" href="http://cdn.jsdelivr.net/animatecss/2.1.0/animate.min.css"><!-- load animate -->
	 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	 <script src="js/bootstrap.min.js"></script>
	 <link rel="stylesheet" href="css/style.css">
	 <meta charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 </head>
<body>

 

 <div class="container">

   <div class="starter-template">
     <h1>Temporary Authentication</h1>
     <h3 style="color:red"> CAS is broken. Please access the hour manager by entering your <b> Western UNIVERSAL LOGIN</b>. Please watch for typos as you won't
     	get reminder emails and be able to delete your shift if you mess up. Please do not lie. This is an honor system and putting in a different person's
     	user ID will cause headaches.<br> The "secret" is one of our STC passwords...think egg shaped balls </h3>
     <form action = "index.php" method="post">
     	Western Universal Id: <input type = "text" name="uid"><br>
     	Secret: <input type = "password" name="password"><br>
     	<input type="submit">
     </form>
   </div>

 </div><!-- /.container -->


 <!-- Bootstrap core JavaScript
 ================================================== -->
 <!-- Placed at the end of the document so the pages load faster -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
 <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
 <script src="../../dist/js/bootstrap.min.js"></script>
 <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
 <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>