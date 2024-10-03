<?php // index.php

// Authors: Christian Dees & Aitiana Mondragon

require_once "../play/Game.php";
require_once "../play/Board.php";
require_once "../play/Piece.php";
require_once "../play/SmartStrategy.php";
require_once "../play/RandomStrategy.php";

define('STRATEGY', 'strategy');                     
define('GAMES_DIR', '../../../../writable/');
define('PIDS_FILE', '../../../../writable/pids.json'); 
// COMMENT TOP AND UNCOMMENT BOTTOM FOR SERVER, TOP IS FOR LOCAL
//define('GAMES_DIR', '../writable/');
//define('PIDS_FILE', '../writable/pids.json'); 
$strategies = ["smart", "random"];

// if stategy is entered
$strat_entered = array_key_exists(STRATEGY, $_GET);

// get pids from file
$pids = [];
if (file_exists(PIDS_FILE)) {
    $pids = json_decode(file_get_contents(PIDS_FILE), true) ?: [];
}

// if strat 
if ($strat_entered) {
    // grab entered strategy
    $strategy_raw = $_GET[STRATEGY];
    $strategy = strtolower($strategy_raw);
    $type_strat_exists = in_array($strategy, $strategies);
    // if strategy entered exists
    if ($type_strat_exists) {
        // get unique pid
        do {
            $pid = uniqid();
        } while (in_array($pid, $pids));
        // store pid and new game
        $pids[] = $pid; 
        $game_file = GAMES_DIR . $pid . '.json';
        // setup new game 
        $game = new Game();
        $game->board = new Board(15);
        // handle strategies
        $strategy = ucfirst($strategy) . 'Strategy';
        $game->strategy = new $strategy($game->board);
        
        // store game & append to list of pids
        file_put_contents($game_file, $game->to_json());
        file_put_contents(PIDS_FILE, json_encode($pids));
        $response = array("response" => true, "pid" => $pid);
        
    } else {
        $response = array("response" => false, "reason" => "Unknown strategy");
    }
} else {
    $response = array("response" => false, "reason" => "Strategy not specified");
}
echo json_encode($response);
?>
