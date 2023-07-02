<?php
include("db_connection.php");
$db=new db_connection();
if(isset($_POST['Email'])&&isset($_POST['FirstName'])&&isset($_POST['LastName'])&&isset($_POST['Password'])){
    $Email=$_POST['Email'];
    $FirstName=$_POST['FirstName'];
    $LastName=$_POST['LastName'];
    $Password=$_POST['Password'];
}
try{
    $db->query("Insert into login_signin(Email_ID,First_Name,Last_Name,Password) values ('$Email','$FirstName','$LastName','$Password')");
    $db->close();
    echo "<h2> <font color=red>Welcome {$FirstName}!! Your User ID has been registered successfully...</font> <h2>";
    echo "<div style='position: fixed; bottom: 0;color: chocolate; width: 100%; background-color: #f0f0f0; padding: 10px; text-align: center;'><a href='../HTML/Login.html' style='color: chocolate;'>Click here</a> to login</div>";
}
catch(Exception $e){
    echo "<h2> <font color=red>User ID is Already Exist!!...</font> <h2>";
}

?>
