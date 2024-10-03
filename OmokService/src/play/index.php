<?php // index.php

// Authors: Christian Dees & Aitiana Mondragon

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
                if (!$response) {
                    // if successfully moved player piece
                    [$draw, $won, $plyr_win_row, $sys_win_row] = $game->game_status();
                    $player_won = $won && $plyr_win_row;
                    $sys_won = $won && $sys_win_row;
                    $sys_cords = [];
                    if (!($draw || $won)) {
                        // continue with system move
                        $sys_cords = $game->strategy->pick_place();
                        if ($sys_cords) {
                            $response = $sys_piece->place($sys_cords[0], $sys_cords[1]);
                            if (!$response) {
                                $sys_won = $game->win_info(1)[0];
                                [$draw, $won, $plyr_win_row, $sys_win_row] = $game->game_status();
                                $player_won = $won && $plyr_win_row;
                                $sys_won = $won && $sys_win_row;
                            }
                        } else {
                            $response = array("response" => false, "reason" => "There are no more empty spots left on the board.");
                        }
                    }
                    // setup player response
                    $player_response = [
                        "ack_move" => [
                            "x" => (int)$input[XCORD],
                            "y" => (int)$input[YCORD],
                            "isWin" => $player_won,
                            "isDraw" => $draw,
                            "row" => $plyr_win_row 
                        ]
                    ];
                    // setup system response
                    if (!empty($sys_cords)) {
                        $sys_response = [
                            "move" => [
                                "x" => (int)$sys_cords[0],
                                "y" => (int)$sys_cords[1],
                                "isWin" => $sys_won,
                                "isDraw" => $draw,
                                "row" => $sys_win_row 
                            ]
                        ];
                    } else {
                        $sys_response = [];
                    }
                    // formulate final response 
                    if (!$response){
                        $response = array_merge(["response" => true], $player_response, $sys_response);
                    }
                    if ($won || $draw){
                        // remove game file and pid from pids.json
                        unlink($game_file);
                        $pid_idx = array_search($pid, $pids);
                        unset($pids[$pid_idx]);
                        file_put_contents(PIDS_FILE, json_encode($pids));
                    } else {
                        // save game state
                        file_put_contents($game_file, $game->to_json());
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
?>
