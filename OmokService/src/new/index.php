<?php // index.php
require_once "../play/Game.php";
require_once "../play/Board.php";
require_once "../play/Player.php";
require_once "../play/SmartStrategy.php"; // Ensure SmartStrategy is included
require_once "../play/RandomStrategy.php"; // Ensure RandomStrategy is included

define('STRATEGY', 'strategy');
define('GAMES_DIR', '../writable/games/');
$strategies = ["smart", "random"];
$strat_exists = array_key_exists(STRATEGY, $_GET);
$pids = array();
global $usr_player, $sys_player, $game, $strategy, $file;
if ($strat_exists) {
    $strategy_raw = $_GET[STRATEGY];
    $strategy = strtolower($strategy_raw);
    $type_strat_exists = in_array($strategy, $strategies);
    
    if ($type_strat_exists) {
        // Generate unique game ID
        $pid = uniqid();
        $pids[] = $pid;
        $file = GAMES_DIR . $pid . '.json'; // Create a filename using the pid
        if (file_exists($file)) {
            // Load existing game state
            $json = file_get_contents($file);
            $game = Game::fromJson($json);
        } else{
            // Initialize game
            $game = new Game(); // Move this line up to instantiate the game first

            // Initialize the board
            $game->board = new Board(15); // You need to have a Board class defined

            // Initialize strategy based on the provided strategy
            if ($strategy === 'smart') {
                $game->strategy = new SmartStrategy($game->board); // Assuming SmartStrategy is defined
            } else if ($strategy === 'random') {
                $game->strategy = new RandomStrategy($game->board);
            }

            // Initialize players
            $sys_player = new Player(1);
            $usr_player = new Player(2);
        }

        $response = array("response" => true, "pid" => $pid);
        
    } else {
        $response = array("response" => false, "reason" => "Unknown strategy");
    }
} else {
    $response = array("response" => false, "reason" => "Strategy not specified");
}

echo json_encode($response, JSON_PRETTY_PRINT);


function storeState($file, $gameJson) {
    file_put_contents($file, $gameJson);
}
?>