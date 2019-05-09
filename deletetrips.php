<?php
//start session and connect
session_start();
include('connection.php');
$sql="DELETE FROM ridesharetrips WHERE trip_id='".$_POST['trip_id']."'";
$result = mysqli_query($link, $sql);
?>