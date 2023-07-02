<?php
include("db_connection.php");
$db=new db_connection();
$Email1=$_POST['Email'];
if(isset($_POST['Email'])&&isset($_POST['Password'])){
    $Email=$_POST['Email'];
    $Password=$_POST['Password'];
}
$result=$db->query("Select * from login_signin where Email_ID='$Email' AND Password='$Password'");
$var=mysqli_num_rows($result);
$row=$result->fetch_assoc();
if($var==1){
        if($row['Email_ID']==$Email&& $row['Password']==$Password){
            header("Location: http://localhost/Airplane_Project/HTML/BOOKING%20INFO.html");
        }
        else{
            echo "<h2> <font color=red>Incorrect Username or Password..</font> <h2>";
        }
}
else{
    echo "<h2> <font color=red>Incorrect Username or Password..</font> <h2>";
}
$db->close();
session_start();
$_SESSION['Mail']=$Email
?>