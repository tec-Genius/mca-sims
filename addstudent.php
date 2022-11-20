
<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
include('admin/connect.php');
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
if(isset($_REQUEST["term"])){
    // Prepare a select statement
    $sql = "SELECT * FROM student WHERE lastname LIKE ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        
        // Set parameters
        $param_term = $_REQUEST["term"] . '%';
        $subj=$_REQUEST["sid"];
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
				?>
             <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" style="font-size:10pt">
                                <thead>
                                    <tr>

                                        <th>StudentID</th>
                                        <th>Lastname</th>
                                        <th>Firstname</th>
                                        <th>Gender</th>
                                        <th>Program</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                               
                                    
                <?php
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        $id=$row['id'];
                                        $p=$row['cys'];
                                        $query2 = mysqli_query($conn,"select * from course where course_id='$p'") or die(mysqli_error($conn));
                                        $pro=mysqli_fetch_array($query2);
                                        ?>                                 
                                    <tr class="odd gradeX">
                                        <td><?php echo $row['student_id']; ?></td>
                                        <td><?php echo $row['lastname'] ; ?></td> 
                                        <td><?php echo $row['firstname'] ; ?></td> 
                                          <td><?php echo $row['gender']; ?></td> 
                                        <td><?php  echo $p?></td> 
                                        <td>
                                            <a id="press" href="teacher_add_student.php?subid=<?php echo $subj; ?>&student=<?php echo $row['id'] ?>"  class="btn btn-success"><li class="icon-plus icon-large"></li>&nbsp;Add</a>
                                        </td>                                  
                                    </tr>
                <?php
                }
                ?>
			</table>
            <?php
            } else{
                echo "<p>No matches found</p>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
}
 
// close connection
mysqli_close($conn);
?>