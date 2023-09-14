<?php
header('Content-Type: application/json');
include("db_connection.php");

$db = new db_connection();

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (isset($data['Email']) && isset($data['Password'])) {
        $Email = $data['Email'];
        $Password = $data['Password'];
        $result = $db->query("SELECT * FROM login_signin WHERE Email_ID='$Email' AND Password='$Password'");
        if ($result->num_rows == 1) {
            $_SESSION['Mail'] = $Email; 
            $_SESSION['LAST_ACTIVITY'] = round(microtime(true) * 1000);
            echo json_encode(array(
                'Login' => true,
                'status code' => 200,
                'message'=> 'Login successful',
            ));
            exit;
        } 
        else {
            session_unset();
            session_destroy();
            echo json_encode(array(
                'Login' => false,
                'status code' => 201,
                'error' => 'Incorrect username or password'
            ));
        }
    }
    else {
        $response = array('success' => false, 'message' => "Invalid request parameters.");
        echo json_encode($response);
    }
}

$db->close();
?>
