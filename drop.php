<html>
<head>
  <title>Deleting Shift</title>
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
 <?php
/*
  drop.php

  Author: Sasa Vukovic
  Date Created: October 2015

  Drops a shift from table if user decides to 
  delete from index.

  Shift author must be same as user logged in.
*/

  if(isset($_SESSION['uid'])){
    //Already have a session
  }else{
    session_start();
  }

  //set up mysql connection
  $link = mysql_connect("localhost", "root", "<PASSWORD HERE>") or die(mysql_error());
  //select database
  mysql_select_db("hourTracker", $link) or die(mysql_error());

  $shiftID = $_GET["id"];
  $result = mysql_query("SELECT * FROM HR_DATA WHERE id=$shiftID", $link) or die(mysql_error());

  while ($row = mysql_fetch_array($result)){
    $uid = $row['uid'];
    if ($_SESSION['uid']==$uid){
      $sql = "DELETE FROM HR_DATA WHERE id=$shiftID";
      if (mysql_query($sql, $link) === TRUE) {
          echo "<div>Record deleted successfully. Bringing you back to the homepage.</div>";
      } else {
          echo "Error deleting record: " . $link->error;
      }
    }
    else {
      echo "Invalid credentials. You are probably not the person that gave the shift up.<br> Please log in with proper ID.";
    }
  }
  header( "refresh:2;url=index.php");

?>
</body>

</html>

