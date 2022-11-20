<?php
if(isset($_GET['send']))
{
$sname=$_GET['sname'];
$results=mysqli_query($conn,"select * from results where uid='$sname' and category='1'") or die(mysqli_error($conn));
if(mysqli_num_rows($results)==0)
{
echo "<div class='alert alert-danger'><i class='icon-remove-sign'></i> &nbsp;No results found for student!</div>";
}
else
{//student results found
$full=mysqli_query($conn,"select * from student where id='$sname'")or die(mysqli_error($conn));
$p=mysqli_fetch_array($full);
$pr=$p['cys'];
$belong=mysqli_query($conn,"select * from course where course_id='$pr'")or die(mysqli_error($conn));
$get=mysqli_fetch_array($belong);
$joined=$p['joined_in'];
if($joined==1){$nums=$get['first'];$y='first year enlrolled';}if($joined==2){$nums=$get['second'];$y='second year excemption';}if($joined==3){$nums=$get['third'];$y='third year exemption';}
$select=mysqli_query($conn,"select distinct uid, course_code,comment from results where uid='$sname' and comment<>'F' and category='1'")or die(mysqli_error($conn));
$dd=mysqli_num_rows($select);
if($dd<$nums)
{//less subjects done
echo "<div class='alert alert-danger'><i class='icon-remove-sign'></i>&nbsp;<b>Less number of subjects encountered! $pr($y) Students should do a total of $nums subjects,student did $dd subjects.Transcript generation was aborted! $sname</div>";
}//end less subjects
/*else
{
$fail1=mysqli_query($conn,"select * from results where uid='$sname' and comment='F' and repeated='1'")or die(mysqli_error($conn));
if(mysqli_num_rows($fail1)>0){ //failed repeat found
$yes=mysqli_fetch_array($fail1);
$co=$yes['course_code'];
$passed=mysqli_query($conn,"select * from results where uid='$sname' and course_code='$co' and comment<>'F'") or die(mysqli_error($conn));
if(mysqli_num_rows($passed)==0){
$se=mysqli_query($conn,"select * from subject where subject_code='$co'") or die(mysqli_error($conn));
$codes=mysqli_fetch_array($se);
echo "<div class='alert alert-danger'><i class='icon-remove-sign'></i>&nbsp;<b>$codes[subject_title] </b>was failed in $yes[year] semester $yes[studsem] repeated but not passed Generation was aborted!</div>";
}//close failed repeat*/
else
{// open all subjects done
$fail=mysqli_query($conn,"select * from results where uid='$sname' and comment='F' and category='1'")or die(mysqli_error($conn));
if(mysqli_num_rows($fail)>0){ //fail found
$avcode=mysqli_fetch_array($fail);
$ccode=$avcode['course_code'];
$search=mysqli_query($conn,"select * from results where uid='$sname' and course_code='$ccode' and comment<>'F' and category='1'")or die(mysqli_error($conn));
if(mysqli_num_rows($search)>0){//failed subject repeated and passed-generate transcript
include('transbody.php');
}//close failed but repeated and passed
else
{//open failed course found but not repeated and passed
echo "<div class='alert alert-danger'><i class='icon-remove-sign'></i>Cannot Generate Transcript Student has Failed Course(s)$ccode</div>";
?>
<table id="example" align="center" class="table table-striped table-bordered">
<tr class="alert-info"><td>SUBJECT CODE</td><td>SUBJECT TITTLE</td><td>FAILED YEAR</td><td>SEM</td><td>YEAR</td></tr>
<?php
$subject=mysqli_query($conn,"select * from subject where subject_code='$ccode' and category='1'")or die(mysqli_error($conn));
$failedsub=mysqli_fetch_array($subject);
?>
<tr><td><?php echo $ccode ?></td><td><?php echo $failedsub['subject_title'] ?></td><td><?php echo $avcode['stud_year'] ?></td><td><?php echo $avcode['studsem'] ?></td><td><?php echo $avcode['year'] ?></td></tr>
<?php
?>
</table>
<?php
}//close failed but not repeated and passed
}//close failed
else
{//no fail found
include('transbody.php');
}//no fail found
}//close all subjects done
}//close student results found
}//close if isset

?>