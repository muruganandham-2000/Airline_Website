<?php
include("db_connection.php");
session_start();
$my_value = $_SESSION['Mail'];
$db=new db_connection();
if(isset($_POST['From'])&&isset($_POST['To'])&&isset($_POST['Depart'])&&isset($_POST['Return'])&&isset($_POST['Adult'])&&isset($_POST['Child'])&&isset($_POST['Trav_cls'])){
    $From=$_POST['From'];
    $To=$_POST['To'];
    $Depart=$_POST['Depart'];
    $Return=$_POST['Return'];
    $Adult=$_POST['Adult'];
    $Child=$_POST['Child'];
    $Travel_cls=$_POST['Trav_cls'];
    $current_time = time();
    $current_date_time = date('Y-m-d H:i:s', $current_time);
}
if ($From!=$To){
    $result=$db->query("Select Amount from amount_table where start='$From' AND end='$To'");
    $var=mysqli_num_rows($result);
    $row=$result->fetch_assoc();
    if($var==1){
        if ($Travel_cls=='Business Class'){$amount=($row['Amount']*$Adult+$row['Amount']/2*$Child)*2;}
        else{$amount=$row['Amount']*$Adult+$row['Amount']/2*$Child;}
        if (empty($Return)){}else{$amount*=2;}
        $db->query("Insert into booking_table(User_ID,Fly_From,Fly_To,Departing,Returning,Adults,Children,Class,Amount,Time) values ('$my_value','$From','$To','$Depart','$Return','$Adult','$Child','$Travel_cls','$amount','$current_date_time')");
        $db->close();
        include 'booking2.php';
    }else{echo "<h2> <font color=red>Something went wrong...!</font> <h2>";}
}else{echo "<h2> <font color=red>Something went wrong...!</font> <h2>";}
?>

