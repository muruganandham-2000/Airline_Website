<?php
header('Content-Type: application/json');
include("db_connection.php");

$db = new db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (isset($data['Email']) && isset($data['Password'])) {
        $Email = $data['Email'];
        $Password = $data['Password'];
        $result = $db->query("SELECT * FROM login_signin WHERE Email_ID='$Email' AND Password='$Password'");
        if ($result->num_rows == 1) {
            session_start();
            $_SESSION['Mail'] = $Email;
            echo json_encode(array(
                'Login' => true,
                'status code' => 200,
                'message'=> 'Login successful',
            ));
            exit;
        } else {
            // Destroy any previous session data
            session_start();
            session_unset();
            session_destroy();
        }
    }
}

echo json_encode(array(
    'Login' => false,
    'status code' => 201,
    'error' => 'Incorrect username or password'
));
$db->close();
?>
