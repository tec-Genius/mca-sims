<?php
include('connect.php');
$get_id=$_GET['id'];
mysqli_query($conn,"delete from sy where sy_id='$get_id'")or die(mysqli_error($conn));
header('location:sy.php');
?>
