<?php // index.php
// inmport files
ob_start();
require_once '../new/index.php';
ob_end_clean();
require_once 'Piece.php';
require_once 'Game.php';

// define constants
define('PID', 'pid');
define('XCORD', 'x');
define('YCORD', 'y');

// grab url input
$input = $_GET;
$response = [];
// DELETE 
$game = null;
// DELETE

$pid_entered = array_key_exists(PID, $input);
if ($pid_entered) {
    // check pid existance
    $pid = $input[PID];
    $pid_exists = in_array($pid, $pids);
    if ($pid_exists) {
        // check if x specified
        $x_entered = array_key_exists(XCORD, $input);
        if ($x_entered) {
            // check if y specified
            $y_entered = array_key_exists(YCORD, $input);
            if ($y_entered) {
                // load game
                $game_file = GAMES_DIR . $pid . '.json';
                $json = file_get_contents($game_file);
                $game = Game::fromJson($json);
                // create new pieces each turn
                $sys_piece = new Piece(1, $game->board);
                $usr_piece = new Piece(2, $game->board);
                // grab response if invalid cords
                $response = $usr_piece->place($input[XCORD], $input[YCORD]);
                if (!$response){
                    // system's move
                    $sys_cords = $game->strategy->pickPlace();
                    if ($sys_cords) {
                        $sys_piece->place($sys_cords[0], $sys_cords[1]);
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
                        // save game state
                        file_put_contents($game_file, $game->toJson());
                    } else {
                        return array("response" => false, "reason" => "There are no more empty spots left on the board.");
                    }
                }
            } else {
                $response = array("response" => $y_entered, "reason" => "y not specified");
            }
        } else {
            $response = array("response" => $x_entered, "reason" => "x not specified");
        }
    } else {
        $response = array("response" => $pid_exists, "reason" => "Unknown pid");
    }
} else {
    $response = array("response" => $pid_entered, "reason" => "Pid not specified");
}

echo json_encode($response, JSON_PRETTY_PRINT);
// DELETE THIS LATER,:
if ($game !== null){
    $game->board->display();
}
?>
