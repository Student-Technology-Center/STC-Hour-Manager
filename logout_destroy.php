<?php
session_start();
//Lets just nuke the session.
session_destroy();
$_SESSION = array();
header('Location: index.php');
?>