<?php // index.php
define('STRATEGY','strategy');
$strategies=["smart", "random"];
$strat_exists=array_key_exists(STRATEGY, $_GET);


if ($strat_exists) {
    $strategy_raw = $_GET[STRATEGY];
    $strategy = strtolower($strategy_raw);
    $type_strat_exists = in_array($strategy, $strategies);
    if ($type_strat_exists){
        $pid = uniqid();
        $response = array("response"=>$type_strat_exists, "pid"=>$pid);
    } else {
        $response = array("response"=>$type_strat_exists, "reason"=>"Unknown strategy");
    }
} else {
    $response = array("response"=> $strat_exists, "reason"=>"Strategy not specified");
}
echo json_encode($response, JSON_PRETTY_PRINT);
?>