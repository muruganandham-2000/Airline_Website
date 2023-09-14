<?php
include("db_connection.php");

if (session_start()) {
    $my_value = isset($_SESSION['Mail']) ? $_SESSION['Mail'] : null;
    $last_act = isset($_SESSION['LAST_ACTIVITY']) ? $_SESSION['LAST_ACTIVITY'] : null;
}

if (isset($_SESSION['LAST_ACTIVITY']) && (round(microtime(true) * 1000) - $_SESSION['LAST_ACTIVITY'] > 60000)) {
    session_unset();
    session_destroy();
}

$db = new db_connection();
$data = json_decode(file_get_contents("php://input"), true);

$codes_list=array("501","502","503","504","601","602","603","604","701","702","703","901","902","903","401","402","403","605");
$message_list=array(
    "Request Sent Successfully",
    "Booking Created Successfully",
    "Amount is not pulling from DB",
    "From and To cannot be same",
    "Parameters Missing",
    "Please login before booking",
    "From field is missing",
    "To field is missing",
    "Depart date is empty",
    "Invalid Departure date",
    "Past Departure date",
    "Invalid Return field",
    "Past Return Date",
    "Both Adult and Child field is missing",
    "Travel class field is missing",
    "Invalid Travel class",
    "Invalid Adult Field",
    "Invalid Child Field",
    "Both Adult & Child Field Cant be Empty",
    "Return date cant be lesser than Depart date"
);

$response_array111[] = array(
    'Booking code' => $codes_list[13],
    'Booking Status' => $message_list[4]);

$current_time = time();
$current_date_time = date('Y-m-d H:i:s', $current_time);
$current_date_RES = date('d-m-Y H:i:s', $current_time);
$current_date = date('d-m-Y', $current_time);
$date_regex = '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-(20\d{2})$/';
$pass_regex='/^(?:0|[1-9][0-9]{0,1}|2[0-9]{2}|300)$/';
$error_message = "";
$err_code="";
$amount=0;
$myArray = [];
$row=null;
$myArray1=[];
$From = isset($data['From']) ? $data['From'] : null;
$To = isset($data['To']) ? $data['To'] : null;
$Depart = isset($data['Depart']) ? $data['Depart'] : null;
$Return = isset($data['Return']) ? $data['Return'] : null;
$Adult = isset($data['Adult']) ? $data['Adult'] : null;
$Child = isset($data['Child']) ? $data['Child'] : null;
$TravelClass = isset($data['Travel Class']) ? $data['Travel Class'] : null;
array_push($myArray1,$my_value,$From,$To,$Depart,$Return,$Adult,$Child,$TravelClass,$amount,$current_date_time);

function addBookingToArray($row,$Err_cd,$Err,$Arry,&$response_array) {
    if (!is_array($response_array)) {
        $res[]=array(
        'Booking code' => $Err_cd,
        'Booking Status' => $Err,
        'User Name' => $Arry[0],
        'From' => $Arry[1],
        'To' =>  $Arry[2],
        'Departure' => $Arry[3],
        'Return' => $Arry[4],
        'Adult' => $Arry[5],
        'Child' =>  $Arry[6],
        'Class' => $Arry[7],
        'Amount' => $Arry[8],
        'Date Time' => $Arry[9]
    );
        return $res;
    }
    $response_array[] = array(
        'Booking code' => 200,
        'Booking Status' => $Err,
        'User Name' => $row['User_ID'],
        'From' => $row['Fly_From'],
        'To' =>  $row['Fly_To'],
        'Departure' => $row['Departing'],
        'Return' => $row['Returning'],
        'Adult' => $row['Adults'],
        'Child' =>  $row['Children'],
        'Class' => $row['Class'],
        'Amount' => $row['Amount'],
        'Date Time' => $row['Time']
    );
    return $response_array;
}

if(isset($data['From']) && isset($data['To']) && isset($data['Depart']) && isset($data['Return']) && isset($data['Adult']) && isset($data['Child']) && isset($data['Travel Class'])) {
    $From = $data['From'];
    $To = $data['To'];
    $Depart = $data['Depart'];
    $Return = $data['Return'];
    $Adult = !empty($data['Adult']) ? $data['Adult'] : 0;
    $Child = !empty($data['Child']) ? $data['Child'] : 0;
    $Travel_cls = $data['Travel Class'];    
    array_push($myArray,$my_value,$From,$To,$Depart,$Return,$Adult,$Child,$Travel_cls,$amount,$current_date_time);
    
    if (empty($my_value)){
        $err_code=$codes_list[0];
        $error_message .= $message_list[5];
        goto ErrorMessage;
    }

    if (empty($From)) {
        $err_code=$codes_list[1];
        $error_message .= $message_list[6];
        goto ErrorMessage;
    }
    if (empty($To)) {
        $err_code=$codes_list[2];
        $error_message .= $message_list[7];
        goto ErrorMessage;
        }
    
    if (empty($Depart)) {
        $err_code=$codes_list[3];
        $error_message .= $message_list[8];
        goto ErrorMessage;
    } elseif (!preg_match($date_regex, $Depart)) {
        $err_code=$codes_list[4];
        $error_message .= $message_list[9];
        goto ErrorMessage;
    } elseif (strtotime($Depart) < strtotime($current_date)) {
        $err_code=$codes_list[5];
        $error_message .= $message_list[10];
        goto ErrorMessage;
    }
    
    if (!empty($Return)) {
        if (!preg_match($date_regex, $Return)) {
            $err_code=$codes_list[6];
            $error_message .= $message_list[11];
            goto ErrorMessage;
        } elseif (strtotime($Return) < strtotime($current_date)) {
            $err_code=$codes_list[7];
            $error_message .= $message_list[12];
            goto ErrorMessage;
        }
    }

    if (strtotime($Return)<strtotime($Depart)){
        $err_code = $codes_list[17];
        $error_message .= $message_list[19];
        goto ErrorMessage;
    }
    
    if ($Adult == 0 && $Child == 0) {
        $err_code = $codes_list[16];
        $error_message .= $message_list[18];
        goto ErrorMessage;
    }
    
    if (!preg_match($pass_regex, $Adult)) {
        $err_code = $codes_list[14];
        $error_message .= $message_list[16];
        goto ErrorMessage;
    }
    
    if (($Child > 0) && (!preg_match($pass_regex, $Child))) {
        $err_code = $codes_list[15];
        $error_message .= $message_list[17];
        goto ErrorMessage;
    }
    
    if (empty($Travel_cls)) {
        $err_code=$codes_list[9];
        $error_message .= $message_list[14];
        goto ErrorMessage;
    } elseif ($Travel_cls != 'Business Class' && $Travel_cls != 'Economy Class') {
        $err_code=$codes_list[10];
        $error_message .= $message_list[15];
        goto ErrorMessage;
    }
    
    ErrorMessage:
    if (!empty($error_message)) {
        echo json_encode(array('Data'=>addBookingToArray($row,$err_code,$error_message,$myArray,$response_array),"Status Code" => 200,"Status" => "Success","Message" => $message_list[0]));
        exit;
    }

    if ($From != $To) {
        $result = $db->query("Select Amount from amount_table where start='$From' AND end='$To'");
        $var = mysqli_num_rows($result);
        $row = $result->fetch_assoc();
        if($var == 1) {
            if ($Travel_cls == 'Business Class') {
                $amount = ($row['Amount'] * $Adult + $row['Amount'] / 2 * $Child) * 2;
            } 
            if($Travel_cls == 'Economy Class') {
                $amount = $row['Amount'] * $Adult + $row['Amount'] / 2 * $Child;
            }
            if (empty($Return)) {} else {
                $amount *= 2;
            }
            $db->query("Insert into booking_table(User_ID,Fly_From,Fly_To,Departing,Returning,Adults,Children,Class,Amount,Time) values ('$my_value','$From','$To','$Depart','$Return','$Adult','$Child','$Travel_cls','$amount','$current_date_time')");
            $sss=$db->query("SELECT * FROM pydatabase.booking_table order by Time desc LIMIT 1");
            $var=mysqli_num_rows($sss);
            $db->close();
            if ($var>0) {
                $response_array = array();
                while ($row = $sss->fetch_assoc()){
                    addBookingToArray($row,$err_code,$message_list[1],$myArray,$response_array);
                }
                echo json_encode(array(
                    'Data' => $response_array,
                    'Status code' => 200,
                    'Status' => 'Success',
                    'Message' => $message_list[0]
                    //'Booking created successfully'
                ));
            //session_start();
            //session_unset();
            //session_destroy();
            }
        } else {
            echo json_encode(array('Data'=>addBookingToArray($row,$codes_list[11],$message_list[2],$myArray,$response_array),"Status Code" => 200,"Status" => "Success","Message" => $message_list[0]));
        }
    } else {
        echo json_encode(array('Data'=>addBookingToArray($row,$codes_list[12],$message_list[3],$myArray,$response_array),"Status Code" => 200,"Status" => "Success","Message" => $message_list[0]));
    }
} else {
    echo json_encode(array("Data"=>addBookingToArray($row,$codes_list[13],$message_list[4],$myArray1,$response_array),"Status Code" => 200,"Status" => "Success","Message" => $message_list[0]));
}
?>