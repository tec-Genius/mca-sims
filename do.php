<?php
include('admin/functions.php');
session_start();
$id=$_SESSION['id'];
$myauth=mysqli_query($conn,"select * from teacher where teacher_id='$id' and auth=1")or die(mysqli_error($conn));
if(mysqli_num_rows($myauth)==0){
echo json_encode(array("err"=>"Invalid account"));
}
else
{
//$asz=$_GET['ass_no'];
if(empty($_GET['ass_no'])){
echo json_encode(array("err"=>"Please select assignment No"));
}
else
{
if($_GET['grade']==''){
echo json_encode(array("err"=>"Please enter grade"));
}
			   else
			   { //start assno			   
			    $year= date('Y');
			   $day=getdate();
			   $d= $day['yday'];
			   $sem2=checksem();
			   
			   //$es=mysqli_query($conn,"select * from closing_dates where  year='$year' and sem='$sem2'") or die(mysqli_error($conn));
			   //$es1=mysqli_fetch_array($es);
			  // $dueeos=$es1['day'];
			 $code=  $_GET['ccode'];
             $stud=  $_GET['stud_id'];
			   $ass_no=$_GET['ass_no'];
			  $fn=clean($conn,$_GET['fn']);
			   $ln=clean($conn,$_GET['ln']);
			    $cy=$_GET['cyear'];
				 $prog=$_GET['prog'];
				  $mode=$_GET['mode'];
				  $dept=$_GET['dept'];
				   //$add=$_GET['adds'];
				  $my=$_GET['myid'];
                                   $sem = $_GET['sem'];
								   $add=$_SESSION['id'];	 
              $grade = clean($conn,$_GET['grade']);
			   if(($grade>100) || ($grade<0) ||(is_numeric($grade)==false)){//inalid grade
			 echo json_encode(array("err"=> "Invalid grade"));
			  }
			  else{//start valid
$query = mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2' ") or die(mysqli_error($conn));
$get=mysqli_fetch_array($query);
                $count = mysqli_num_rows($query);
                    if ($count == 0) { //start count=0
					if ($ass_no==1){ //start ass==1
				$es=	calculateEOS($grade,0,0);
				$rate=rateEOS($es);
				mysqli_query($conn,"insert into results(uid,student_id,firstname,surname,course_code,assign_1,year,sem, fgrade,comment,prog,mode,stud_year,dept,studsem,byw)values('$stud','$my','$fn','$ln','$code','$grade','$year','$sem2','$es','$rate','$prog','$mode','$cy','$dept','$sem','$add')") or die(mysqli_error($conn));
$up=mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'");
$myupp=mysqli_fetch_assoc($up);
$ax1=$myupp['assign_1'];
$ax2=$myupp['assign_2'];
$exa=$myupp['EOS'];
$fg=$myupp['fgrade'];
$com=$myupp['comment'];
if($ax1==0 or $ax2==0 or $exa==0) $WAT="N/A"; else $WAT=$com;              	
 echo json_encode(array("err"=>"grade added","as1"=>$ax1,"as2"=>$ax2,"exa"=>$exa,"fg"=>$fg,"com"=>$WAT));					
			}//end ass==1
				if($ass_no==2){//start ass==2
				$es=	calculateEOS( 0,$grade,0);
				$rate=rateEOS($es);
			    mysqli_query($conn,"insert into results(uid,student_id,firstname,surname,course_code,assign_2,year,sem,fgrade,comment,prog,mode,stud_year,dept,studsem,byw) 
				values('$stud','$my','$fn','$ln','$code','$grade','$year','$sem2','$es','$rate','$prog','$mode','$cy','$dept','$sem','$add')") or die(mysqli_error($conn));
$up=mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'");
$myupp=mysqli_fetch_assoc($up);
$ax1=$myupp['assign_1'];
$ax2=$myupp['assign_2'];
$exa=$myupp['EOS'];
$fg=$myupp['fgrade'];
$com=$myupp['comment'];
if($ax1==0 or $ax2==0 or $exa==0) $WAT="N/A"; else $WAT=$com;              	
 echo json_encode(array("err"=>"grade added","as1"=>$ax1,"as2"=>$ax2,"exa"=>$exa,"fg"=>$fg,"com"=>$WAT));				
				}//end ass==2
				if($ass_no==3){//start ass==3
				$es=	calculateEOS( 0,0,$grade);
				$rate=rateEOS($es);
				mysqli_query($conn,"insert into results(uid,student_id,firstname,surname,course_code,EOS,year,sem,fgrade,comment,prog,mode,stud_year,dept,studsem,byw) values('$stud','$my','$fn','$ln','$code','$grade','$year','$sem2','$es','$rate','$prog','$mode','$cy','$dept','$sem','$add')") or die(mysqli_error($conn));
$up=mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'");
$myupp=mysqli_fetch_assoc($up);
$ax1=$myupp['assign_1'];
$ax2=$myupp['assign_2'];
$exa=$myupp['EOS'];
$fg=$myupp['fgrade'];
$com=$myupp['comment'];
if($ax1==0 or $ax2==0 or $exa==0) $WAT="N/A"; else $WAT=$com;              	
 echo json_encode(array("err"=>"Grade added","as1"=>$ax1,"as2"=>$ax2,"exa"=>$exa,"fg"=>$fg,"com"=>$WAT));				
			 //sendresults($stud,$code);
				}//end ass==3
				}//end count=0
				if($count==1)
				{ //start count==1
				if ($ass_no==1){//start ass==1
				
				$sel=	mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2' ") or die(mysqli_error($conn));
				$row=mysqli_fetch_array($sel);
				$ass2=$row['assign_2'];	
				$exam=$row['EOS'];
				 $es=	calculateEOS($grade,$ass2,$exam);
				$rate=rateEOS($es);
				  mysqli_query($conn,"update results set assign_1='$grade',fgrade='$es',comment='$rate', byw='$add', student_id='$my',firstname='$fn',surname='$ln' ,studsem='$sem',sem='$sem2',dept='$dept',prog='$prog',stud_year='$cy' where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'") or die(mysqli_error($conn));
$up=mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'");
$myupp=mysqli_fetch_assoc($up);
$ax1=$myupp['assign_1'];
$ax2=$myupp['assign_2'];
$exa=$myupp['EOS'];
$fg=$myupp['fgrade'];
$com=$myupp['comment'];
if($ax1==0 or $ax2==0 or $exa==0) $WAT="N/A"; else $WAT=$com;              	
 echo json_encode(array("err"=>"Grade added","as1"=>$ax1,"as2"=>$ax2,"exa"=>$exa,"fg"=>$fg,"com"=>$WAT));			  
				  } //end ass==1
				  else if($ass_no==2){//start ass==2
				 $sel=	mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'") or die(mysqli_error($conn));
				$row=mysqli_fetch_array($sel);
				$ass1=$row['assign_1'];	
				$exam=$row['EOS'];
				$es=	calculateEOS($ass1,$grade,$exam);
				$rate=rateEOS($es);			  				  
				mysqli_query($conn,"update results set studsem='$sem', assign_2='$grade',fgrade='$es',comment='$rate', byw='$add',student_id='$my',firstname='$fn',surname='$ln',sem='$sem2',dept='$dept',prog='$prog',stud_year='$cy' where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'") or die(mysqli_error($conn));
$up=mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'");
$myupp=mysqli_fetch_assoc($up);
$ax1=$myupp['assign_1'];
$ax2=$myupp['assign_2'];
$exa=$myupp['EOS'];
$fg=$myupp['fgrade'];
$com=$myupp['comment'];
if($ax1==0 or $ax2==0 or $exa==0) $WAT="N/A"; else $WAT=$com;              	
 echo json_encode(array("err"=>"Grade added","as1"=>$ax1,"as2"=>$ax2,"exa"=>$exa,"fg"=>$fg,"com"=>$WAT));		 
				  }//end ass==2
				  else
				  { //start ass==3
				   $sel=	mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'") or die(mysqli_error($conn));
				$row=mysqli_fetch_array($sel);
				$ass1=$row['assign_1'];	
				$ass2=$row['assign_2'];
				
				$es=	calculateEOS($ass1,$ass2,$grade);
				$rate=rateEOS($es);	
				   mysqli_query($conn,"update results set studsem='$sem',EOS='$grade',fgrade='$es',comment='$rate',byw='$add', student_id='$my',firstname='$fn',surname='$ln',sem='$sem2',prog='$prog',dept='$dept',stud_year='$cy' where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2' ") or die(mysqli_error($conn));
$up=mysqli_query($conn,"select * from results where uid='$stud' and course_code='$code' and year='$year' and sem='$sem2'");
$myupp=mysqli_fetch_assoc($up);
$ax1=$myupp['assign_1'];
$ax2=$myupp['assign_2'];
$exa=$myupp['EOS'];
$fg=$myupp['fgrade'];
$com=$myupp['comment'];
if($ax1==0 or $ax2==0 or $exa==0) $WAT="N/A"; else $WAT=$com;              	
 echo json_encode(array("err"=>"Grade added","as1"=>$ax1,"as2"=>$ax2,"exa"=>$exa,"fg"=>$fg,"com"=>$WAT));				
				}//endass==3
				}//end count=1
				}//end valid
			//	}//end  due date not over
				}//end isset assno
				}//end isset save
}
?>
