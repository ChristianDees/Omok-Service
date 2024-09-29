<?php // index.php
ob_start();
require_once '../new/index.php';
ob_end_clean();
// DELETE THIS LATER
require_once 'Player.php';
require_once 'Game.php';
// DELETE THIS LATER

define('PID', 'pid');
define('XCORD', 'x');
define('YCORD', 'y');
$input = $_GET;
$response = []; 

global $usr_player, $sys_player, $game, $strategy, $file;
/// DELETE THIS LATER
$pids = array('66f8ef22232a2'); 

$game = new Game(); 
$game->board = new Board(15); 

$sys_player = new Player(1, $game->board);
$usr_player = new Player(2, $game->board);

$game->strategy = new RandomStrategy($game->board);
$pid = '66f8ef22232a2';
$file = GAMES_DIR . $pid . '.json';

/// DELETER THIS LATER


$pid_entered = array_key_exists(PID, $input);
if ($pid_entered) {
    $pid = $input[PID];
    $pid_exists = in_array($pid, $pids);
    
    if ($pid_exists) {

        $x_entered = array_key_exists(XCORD, $input);
        
        if ($x_entered) {

            $y_entered = array_key_exists(YCORD, $input);
            if ($y_entered) {
                
                $file = GAMES_DIR . $pid . '.json'; // create filename pid based
                if (file_exists($file)) {
                    // load state if exists
                    $json = file_get_contents($file);
                    $game = Game::fromJson($json);
                    $sys_player = new Player(1, $game->board);
                    $usr_player = new Player(2, $game->board);
                }
                $usr_player->move_to($input[XCORD], $input[YCORD]);
                
                // system's move
                $sys_cords = $game->strategy->pickPlace();
                if ($sys_cords) {
                    $sys_player->move_to($sys_cords[0], $sys_cords[1]);
                }
                
                // win/draw check
                $won = $game->check_win(); 
                $isDraw = $game->check_draw(); 
                // remove pid once game is won or draw
                if ($won || $isDraw) {
                    unset($array[array_search($pid,$pids)]);
                }
                
                // responses
                $response = [
                    "response" => true,
                    "ack_move" => [
                        "x" => $input[XCORD],
                        "y" => $input[YCORD],
                        "isWin" => $won,
                        "isDraw" => $isDraw,
                        "row" => array() // UPDATE WITH STRAT LOGIC
                    ],
                    "move" => [
                        "x" => $sys_cords[0],
                        "y" => $sys_cords[1],
                        "isWin" => $won,
                        "isDraw" => $isDraw,
                        "row" => array() // UPDATE WITH STRAT LOGIC 
                    ]
                ];
                
                storeState($file, $game->toJson());
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
$game->board->display();
?>
