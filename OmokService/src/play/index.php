<?php // index.php
ob_start();
require_once '../new/index.php';
ob_end_clean();

define('PID', 'pid');
define('XCORD', 'x');
define('YCORD', 'y');
$input = $_GET;

$pids = array('66f87fc7bfc6b'); 
$response = []; 


$pid_entered = array_key_exists(PID, $input);
if ($pid_entered) {
    $pid = $input[PID];
    $pid_exists = in_array($pid, $pids);
    
    if ($pid_exists) {

        $x_entered = array_key_exists(XCORD, $input);
        
        if ($x_entered) {

            $y_entered = array_key_exists(YCORD, $input);
            if ($y_entered) {
                $won = false; // CHANGE THIS
                $isDraw = false; // CHANGE THIS
                $ack_move_arr = array("x"=>$input[XCORD], "y"=>$input[YCORD], "isWin"=>$won, "isDraw"=>$isDraw, "row"=>array());
                // CHANGE COORDS
                $move_arr = array("x"=>0, "y"=>0, "isWin"=>$won, "isDraw"=>$isDraw, "row"=>array());

                $response = array("response" => $y_entered, "ack_move" => $ack_move_arr, "move" => $move_arr);
            } else {
                $response = array("response" => $y_entered, "reason" => "y not specified");
            }
        } else {
            $response = array("response" => $x_entered, "reason" => "x not specified");
        }
    } else {
        $response = array("response" => $pid_exists, "reason" => "pid does not exist", "pid" => $pid);
    }
} else {
    $response = array("response" => $pid_entered, "reason" => "PID not specified");
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
