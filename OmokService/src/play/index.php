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
$game = null; //DELETE LATER
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
                $game = Game::from_json($json);
                // create new pieces each turn
                $sys_piece = new Piece(1, $game->board);
                $usr_piece = new Piece(2, $game->board);
                // grab response if invalid cords
                $response = $usr_piece->place($input[XCORD], $input[YCORD]);
                if (!$response){
                    // system's move
                    $sys_cords = $game->strategy->pick_place();
                    if ($sys_cords) {
                        $sys_piece->place($sys_cords[0], $sys_cords[1]);
                        // win/draw check & row assignment
                        $draw_info = $game->draw_info();
                        $win_info = $game->win_info(); 
                        $winner = $win_info[0] ?? 0;
                        $plyr_win_row = array();
                        $sys_win_row =  array();
                        $won = $win_info ? true:false;
                        $draw = $draw_info ? true:false;
                        if ($draw_info){
                            $plyr_win_row = $draw_info[0];
                            $sys_win_row = $draw_info[1];
                        } else if ($winner == 1){
                            $sys_win_row = $win_info[1]; 
                        } else if ($winner == 2) {
                            $plyr_win_row = $win_info[1];
                        } 
                        // setup responses
                        $player_response = [
                            "ack_move" => [
                            "x" => (int)$input[XCORD],
                            "y" => (int)$input[YCORD],
                            "isWin" => $won,
                            "isDraw" => $draw,
                            "row" => $plyr_win_row 
                            ]
                        ];
                        $sys_response = [
                            "move" => [
                            "x" => (int)$sys_cords[0],
                            "y" => (int)$sys_cords[1],
                            "isWin" => $won,
                            "isDraw" => $draw,
                            "row" => $sys_win_row 
                            ]
                        ];
                        // remove necesarry winners row from response
                        $winner == 1 ? $player_response = null : ($winner == 2 ? $sys_response = null:null);
                        // formulate final response 
                        $response = array_merge(["response" => true], $player_response ?? [], $sys_response ?? []);
                        // save game state
                        file_put_contents($game_file, $game->to_json());
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
echo json_encode($response);
// DELETE THIS FOR SERVER
if ($game){
    $game->board->display();
}
?>