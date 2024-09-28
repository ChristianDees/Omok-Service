<?php // index.php
define('STRATEGY', 'strategy'); // constant
$strategies = ["Smart", "Random"]; // supported strategies

// checking if there is even a response
if (!array_key_exists(STRATEGY, $_GET)){
    /* write code here */
    echo '{"response": false, "reason": "Strategy not specified"}';
    exit; 

}
$strategy = $_GET[STRATEGY];

// write your code here … use uniqid() to create a unique play id.

//checks if the strategy is not in the array
if(!in_array($strategy,$strategies)){
    echo '{"response": false, "reason": "Unknown strategy"}';
    exit;
}
// checks if the response is Smart

if($strategy == 'Smart'){
    $pid = uniqid(); // creating unique player ID
    echo '{"response": true, "strategy": "Smart"}';
}


//checks if the response is Random

if($strategy == 'Random'){
    $pid = uniqid(); // creating unique player ID
    echo '{"response": true, "strategy": "Random"}';
}



?>