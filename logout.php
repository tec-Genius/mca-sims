<?php
session_start();
include('admin/connect.php');
$idz=$_SESSION["id"];
mysqli_query($conn,"update notice_view set logged_out='1' where viewer_id='$idz'") or die(mysqli_error($conn));
mysqli_query($conn,"update user set auth='0' where user_id='$idz'") or die(mysqli_error($conn));
	mysqli_query($conn,"update teacher set auth=0 where teacher_id='$idz'");
session_unset('id');
session_unset('level');
session_unset('year');
session_unset('ssem');
session_unset('ADM_YEAR');
session_destroy();
header('location:index.php')
?>
