<?php include('header.php'); ?>
<?php include('session.php'); ?>
<?php include('functions.php'); ?>
<?php
$added_by=$_SESSION['id'];
?>
<body onLoad="StartTimers();" onmousemove="ResetTimers();">

    <div class="row-fluid">
        <div class="span12">

            <?php include('navbar.php');
			
			?>

            <div class="container">

                <div class="row-fluid">
                    <div class="span2" style="margin-top: 50px;">
                        <!-- left nav -->
                        <ul class="nav nav-tabs nav-stacked">

                            <li class="active">
                                <a href="enterfees.php"><i class="icon-plus-sign-alt icon-large"></i>&nbsp;Add fees</a>
                                <a href="file.php"><i class="icon-arrow-left icon-large"></i>&nbsp;Back</a>
                            </li>


                        </ul>
                        <!-- right nav -->
                    </div>
                    <div class="span10">

					<div class="hero-unit-3">
                        <ul class="thumbnails">
                            <li class="span7" style="width:80%">
                                <div class="thumbnail">
                                    <div class="alert alert-info"><i class="icon-plus-sign-alt icon-large"></i>&nbsp;Enter fees for:&nbsp;<b><?php if(isset($_GET['uid']))
$UID=$_GET['uid'];
$sh=checksem();
$sel=mysqli_query($conn,"select * from student where id='$UID'") or die("error ho".mysqli_error($conn));
$row=mysqli_fetch_array($sel);
$se=mysqli_query($conn,"select * from student_fee_amounts") or die("error ho".mysqli_error($conn));
$row2=mysqli_fetch_array($se);

$i=mysqli_query($conn,"select * from interest") or die(mysqli_error($conn));
				$int_am=mysqli_fetch_array($i);
				$int_amount=$int_am['amount'];
				$day=getdate();
                $year_date= $day['yday'];
				$totaldays= $day['yday']-$int_am['year_day'];
									
									 echo $row['firstname']." ".$row['lastname']."(".$row['student_id'].")";?></b></div>
									 <?php
$myauth=mysqli_query($conn,"select * from user where user_id='$added_by' and auth=1")or die(mysqli_error($conn));
if(mysqli_num_rows($myauth)==0){?>
<div class="alert alert-danger"><i class="icon-remove-sign"></i>&nbsp;Access Denied.Wrong account</div>
<?php } else 
        {?>
                                    <form class="form-horizontal" method="POST">
                                        <div class="control-group">
                                            <label class="control-label" for="inputEmail">Fees Amount:</label>
                                            <div class="controls">
                                                <input type="text" name="un" id="inputEmail" placeholder="Amount(MWK)" required>
                                                <input type="hidden" name="id"  value="<?php echo $UID;?>" >
                                                     <select name="month" required style="width:140px"> 
                                              <option value="">Payment Month</option>
                                         
                                               <option value="1">January</option>
                                              <option value="2">February</option>
                                              <option value="3">March</option>
                                               <option value="4">April</option>
                                                <option value="5">May</option>
                                                 <option value="6">June</option>
                                               
                                                  <option value="7">July</option>
                                                   <option value="8">August</option>
                                                    <option value="9">September</option>
                                                      <option value="10">October</option>
                                                        <option value="11">November</option>
                                                          <option value="12">Decemebr</option>
                                                          
                                              </select>
                                             
                                              <select name="date" required style="width:140px"> 
                                              <option value="">Payment Date</option>
                                             <?php    
                                             for($i=1;$i<=31;$i++)
                                               {
                                                ?>
                                                <option value="<?php  echo $i ?>"><?php echo $i; ?></option>
                                                <?php
                                               }
                                             ?>
                                               
                                              </select>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div class="controls">

                                                <button type="submit" name="save" role="button"  class="btn btn-info"><i class="icon-save icon-large"></i>&nbsp;Save</button>
                                            </div>
                                        </div>
                                    </form>
                                        <?php
										$year= date('Y');
										$sem=checksem();
										
										$already= mysqli_query($conn,"select * from student_fees where id='$UID' and year='$year' and sem='$sem'") or die(mysqli_error($conn));
						                $f=mysqli_fetch_array($already); 
										if($f) echo "<font color='red'>Amount already paid:&nbsp;".number_format($f['total_amount'])." Balance:&nbsp;".number_format($f['fee_balance'])."</font>";
										
										?>
                              
                                        
                                 
                                    <?php
                                    if (isset($_POST['save'])) {
                                        $month=$_POST['month'];
										$date=$_POST['date'];
                                        $amount = clean($conn,$_POST['un']);
                                        $id = $_POST['id'];
                                         //$Today = date('y:m:d');
                                         $new = $date."/".$month."/".date('Y');
                                          $sem=checksem();
										  $year= date('Y');
										  //$m=$month;
										  $mode=$row['mode'];
										  $available= mysqli_query($conn,"select * from student_fees where id='$id' and year='$year' and sem='$sem'") or die(mysqli_error($conn));
										  $fees=mysqli_fetch_array($available);
										  $rows=mysqli_num_rows($available);										  
										  //if($mode==1){ $percent=($row2['fulltime']*0.25);}else{$percent=($row2['distance']*0.25);
										  //}
										  //$int=$fees['interest'];
										  //if(((($mode==1)&&($int==0))&&($amount< $percent))|| ((($mode==2)&&($int==0))&&($amount< $percent)))
										  //{
										  //$msg="amount is less than 25% of student fees";
										  //}
										  //else
										 // {
											 $se=mysqli_query($conn,"select * from student_fee_amounts where year=$year and sem=$sem") or die("error ho".mysqli_error($conn));
                                          $row2=mysqli_fetch_array($se);
										  //$interests= calculateinterest($id,$month,$date);
										 // $interests= round($interests,0);
						if($row['mode']==1){$total= $row2['fulltime']; $yfee=$row2['fulltime'];} 
						if($row['mode']==2){ $total=$row2['distance'];$yfee=$row2['distance'];}
						if($row['mode']==3){ $total=$row2['biu'];$yfee=$row2['biu'];} 
						if($row['mode']==4){ $total=$row2['nobiu'];$yfee=$row2['nobiu'];}
						if($rows==0){
						
						
						if ((($mode==1)&&($amount> $row2['fulltime'])) || (($mode==2) && ($amount>$row2['distance'])) || (($mode==3) && ($amount>$row2['biu']))|| (($mode==4) && ($amount>$row2['nobiu']))){
						               $fultime=$row2['fulltime'];
                                        $distance= $row2['distance'];

                            echo "amount more than fees amount".$year." ".$sem;
						}
						else
						{
						
						
						 $balace=$total - $amount;	
						if($balace==0){$exam_no=GenKey(); }else {$exam_no=" ";}
					 mysqli_query($conn,"insert into student_fees (id,amount_paid,fee_balance,year, sem ,last_pay_date,user_id,exam_no,total_amount,pmonth,pay_day) values (' $id','$amount','$balace','$year','$sem','$new','$added_by','$exam_no','$amount','$month','$date')")or die("error here".mysqli_error($conn));
					  ?>
                 <form method="post" action="recipt.php">
                <button class="btn btn-success" type="submit" name="send">&nbsp;Export Receipt to word</button>
                <input type="hidden" value="<?php echo $UID ?>" name="uid" >
                 <input type="hidden" value="<?php echo $amount ?>" name="amount">
                <input type="hidden" value="<?php echo $amount ?>" name="tamount">
                <input type="hidden" value="<?php echo $interests ?>" name="int">
                <input type="hidden" value="<?php echo $balace?>" name="bal" >
                 <input type="hidden" value="<?php echo  $yfee ?>" name="semfee" >
                  <input type="hidden" value="<?php echo $total  ?>" name="total_to_pay" >
               
                </form>
                      
                      <?php
					  
					 $msg="Fees  added click back to go back";
					 $msg2=feeRepofirst($UID,$amount,$amount,$balace,$new,$yfee,$total);
					 ?>
					  <form id="form2">
                      <div id="dvContainer1">
                      <?php
					  printreciept($UID,$amount,$amount,$interests,$balace,$yfee,$total,$new);
					 ?>
                </div>
                <input type="button" value="Print Reciept" id="btnPrint2" class="btn btn-success" />
                </form>
				<?php
					 $totalt= mysqli_query($conn,"select * from total_revenue where year='$year' and sem='$sem'") or die(mysqli_error($conn));
						//updating reservour
						if(mysqli_num_rows($totalt)==0)//some thing has changed
					 {
					  mysqli_query($conn,"insert into total_revenue(t_revenue,year, sem) value('$total','$year','$sem')") or die(mysqli_error($conn));
					  }					 
					 else{//nothing has changed
					  mysqli_query($conn,"update total_revenue set t_revenue=t_revenue+$amount where year='$year' and sem='$sem'") or die(mysqli_error($conn));
					  } 
					}
			         }
							 else{       
							 $mylimit=$fees['fee_balance'];  
							             
										  if($amount>$mylimit){
									   $msg="Entered amount is greater than total fees amounts ";
									   }
									   else
									   {
						                    //$interests=calculateinterest($id,$month,$date);
											 //$interests=round($interests,0);	  
						                      $pmonth=$fees['pmonth'];
											  $pday=$fees['pay_day'];
											  $pyear=$fees['year'];
										  $total_pay=$fees['total_amount']+$amount;
										  
										   
										  $total_balace=  $fees['fee_balance']-$amount;
										  // $total_interest= $fees['interest']+$interests;
										  
									      if($total_balace==0){$exam_no=GenKey(); }else {$exam_no=" ";}
										 
										 
										   mysqli_query($conn,"update total_revenue set t_revenue=t_revenue+$amount,duplicate=duplicate+$amount where year='$year' and sem='$sem'") or die(mysqli_error($conn));
			                             
			mysqli_query($conn,"update student_fees set pay_day='$date',exam_no='$exam_no',fee_balance='$total_balace',total_amount='$total_pay', amount_paid='$amount',last_pay_date='$new',pmonth='$month' where year='$year' and sem='$sem' and id='$id'"); 
			         
			$msg="Fees info updated" ;
			
			//$msg2=feeRepoupdate($UID,$amount,$total_pay,$new,$total_interest,$total_balace,$yfee);
			
			
		?>
              <form method="post" action="recipt2.php">
                <button class="btn btn-success" type="submit" name="send">&nbsp;Export Reciept to word</button>
                
                <input type="hidden" value="<?php echo $UID ?>" name="uid" >
                 <input type="hidden" value="<?php echo $amount ?>" name="amount" >
                <input type="hidden" value="<?php echo $total_pay ?>" name="totalpay" >
                <input type="hidden" value="<?php echo $total_balace?>" name="bal" >
                 <input type="hidden" value="<?php echo  $yfee ?>" name="semfee" >
               
                </form>
                <form id="form1">
    <div id="dvContainer">
        <?php
		printreciept2($UID,$amount,$total_pay,$total_balace,$yfee,$new);
		       ?>
                </div>
                <input type="button" value="Print Reciept" id="btnPrint" class="btn btn-success" />
                </form>
				<?php
			}
			} 
									//}
									}
									if(isset($msg)){ ?>
									<div class="alert alert-info">&nbsp;<?php echo $msg; ?></div><br>
                                    
                                    <?php
									}
									if(isset($msg2)){ ?>
                                    <div class="alert alert-danger">&nbsp;<?php echo $msg2; ?></div>
                                    <?php 
									}

                                    ?>
<?php  }?>
                                </div>
                            </li>

                        </ul>
						</div>



                    </div>
                </div>

                <?php include('footer.php'); ?>
            </div>
        </div>
    </div>




</body>
</html>


