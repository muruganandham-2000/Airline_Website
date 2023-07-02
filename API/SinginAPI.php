<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
include("db_connection.php");
$db=new db_connection();
if(isset($data['Email']) && isset($data['FirstName']) && isset($data['LastName']) && isset($data['Password'])){
    $Email=$data['Email'];
    $FirstName=$data['FirstName'];
    $LastName=$data['LastName'];
    $Password=$data['Password'];
    try{
        $db->query("Insert into login_signin(Email_ID,First_Name,Last_Name,Password) values ('$Email','$FirstName','$LastName','$Password')");
        $db->close();
        $response = array('success' => true,'status code' => 200, 'message' => "Welcome {$FirstName}!! Your User ID has been registered successfully.");
        echo json_encode($response);
    }
    catch(Exception $e){
        $response = array('success' => false,'status code' => 201, 'message' => "User ID is Already Exist!!");
        echo json_encode($response);
    }
}
else {
    $response = array('success' => false, 'message' => "Invalid request parameters.");
    echo json_encode($response);
}
?>
