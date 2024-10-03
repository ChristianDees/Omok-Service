<?php

class Game {
    public $board;
    public $strategy;
    

    // assign game info from json str
    static function from_json($json) {
        $obj = json_decode($json); 
        $board_data = $obj->{'board'};
        $strategy_json = $obj->{'strategy'};
        $game = new Game();
        $game->board = Board::from_json($board_data); 
        $game->strategy = MoveStrategy::from_json($strategy_json, $game->board);
        return $game;
    }

    // json encode game info
    function to_json() {
        return json_encode([
            'board' => $this->board->to_json(),
            'strategy' => $this->strategy->to_json()
        ]);
    }

    // return [player_type_type, winning row]
    function win_info($player_type) {
        // Check if five pieces in a row
        $board = $this->board;
        $size = $board->get_size();
        // check vertical winning row
        for($x=0;$x<$size;$x++){
            for($y=0;$y<=$size - 5;$y++){
                if($board->array_board[$x][$y]== $player_type && $board->array_board[$x][$y + 1]== $player_type && $board->array_board[$x][$y + 2] == $player_type && $board->array_board[$x][$y + 3] == $player_type && $board->array_board[$x][$y + 4] == $player_type){
                    return [true, [$x,$y,$x,$y+1,$x,$y+2,$x,$y+3,$x,$y+4]];;
                }
            }
        }
        // check horizontal winning row
        for($x=0;$x<=$size-5;$x++){
            for($y=0;$y<$size;$y++){
                if($board->array_board[$x][$y]== $player_type && $board->array_board[$x+1][$y]== $player_type && $board->array_board[$x+2][$y]== $player_type && $board->array_board[$x+3][$y]== $player_type && $board->array_board[$x+4][$y]== $player_type){
                    return [true, [$x,$y,$x+1,$y,$x+2,$y,$x+3,$y,$x+4,$y]];
                }
            }
        }
        // check diagonal from top to bottom
        
        for($x=0;$x<=$size-5;$x++){
            for($y=0;$y<=$size-5;$y++){
                if($board->array_board[$x][$y]==$player_type && $board->array_board[$x+1][$y+1]== $player_type && $board->array_board[$x+2][$y+2] == $player_type && $board->array_board[$x+3][$y+3] == $player_type && $board->array_board[$x+4][$y+4] == $player_type){
                    return [true, [$x,$y,$x+1,$y+1,$x+2,$y+2,$x+3,$y+3,$x+4,$y+4]];
                }
            }
        }
        // check diagonal from bottom to top
        for($x=4; $x < $size; $x++){
            for($y=0;$y <= $size - 5; $y++){
                if($board->array_board[$x][$y] == $player_type && $board->array_board[$x-1][$y+1] == $player_type && $board->array_board[$x-2][$y+2]==$player_type && $player_type && $board->array_board[$x-3][$y+3]==$player_type && $player_type && $board->array_board[$x-4][$y+4]==$player_type){
                    return [true, [$x,$y,$x-1,$y+1,$x-2,$y+2,$x-3,$y+3,$x-4,$y+4]];
                }
            }
        }
        return [false, []];
    }

    // return [game draw, game won, player win row, sys win row]
    function game_status(){
        // check if a draw has occured, if so return a list [draw, won, plyr_row, sys_row]
        $player_win_info = $this->win_info(2);
        $sys_win_info = $this->win_info(1);
        $draw = false;
        $won = false;
        $draw = $player_win_info[0] && $sys_win_info[0] ? true : false;
        $won = $player_win_info[0] || $sys_win_info[0] ? true : false;
        return [$draw, $won, $player_win_info[1], $sys_win_info[1]];
    }
}
?>