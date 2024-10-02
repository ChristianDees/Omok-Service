<?php

require_once "Board.php";
require_once "MoveStrategy.php";
    
class SmartStrategy extends MoveStrategy{
    
    function pick_place(){
        // get array of cords that sys takes up
        $board = $this->board;
        $total_cords = array();
        $size = $board->get_size();
        for($x=0;$x<$size;$x++){
            for($y=0;$y<$size;$y++){
                if ($board->array_board[$x][$y] === 1){
                    $total_cords[] = [$x, $y];
                }
            }
        }
        // checks if can win
        $win_place = $this->check_open_end(1);
        if($win_place !== null){
            $total_cords[] = $win_place;
            return $win_place;
        }
        // checks if block is neccessary
        $block_place = $this->check_open_end(2);
        if($block_place !== null){
            $total_cords[] = $block_place;
            return $block_place;
        }
        // if neither, plays offense
        $offense_place = $this->play_smart($total_cords);
        return $offense_place;
    }
    
    function check_open_end($player){
        $board = $this->board;
        $size = $board->get_size();
        //check vertical row
        for($x=0;$x<$size;$x++){
            for($y=0;$y<$size - 2;$y++){
                if($board->array_board[$x][$y]== $player && $board->array_board[$x][$y + 1]== $player && $board->array_board[$x][$y + 2]== $player){
                    // check that the next two spaces are filled
                    // check if the open end is on the top of the row
                    if($y>0 && $board->array_board[$x][$y-1] == 0){
                        return [$x,$y-1];
                    }
                    // check if the open end is on the bottom of the row
                    if($y+3<$size && $board->array_board[$x][$y+3] == 0)
                        return [$x,$y+3];
                }
            }
        }
        //check horizontal row
        for($x=0;$x<$size-2;$x++){
            for($y=0;$y<$size;$y++){
                if($board->array_board[$x][$y]== $player && $board->array_board[$x+1][$y]== $player && $board->array_board[$x+2][$y]== $player){
                    // checks if open end is on left side
                    if($x>0 && $board->array_board[$x-1][$y] == 0){
                        return [$x-1,$y];
                    }
                    // checks if open end is on right side
                    if($x+3<$size && $board->array_board[$x+3][$y] == 0)
                        return [$x+3,$y];
                }
            }
        }
        //check diagonal row (bottom-right)
        for($x=0;$x<$size-2;$x++){
            for($y=0;$y<$size-2;$y++){
                if($board->array_board[$x][$y]==$player && $board->array_board[$x+1][$y+1]== $player && $board->array_board[$x+2][$y+2] == $player){
                    //check if open end is on top-left corner of the diagonal row
                    if($x-1>=0 && $y-1 >= 0 && $board->array_board[$x-1][$y-1]==0){
                        return [$x-1,$y-1];
                    }
                    //check if open end is on bottom-right corner of the diagonal row
                    if($x+3 < $size && $y+3 < $size && $board->array_board[$x+3][$y+3]==0){
                        return [$x+3,$y+3];
                    }
                }
            }
        }
        //check diagonal row (top-right)
        for($x=2; $x < $size; $x++){
            for($y=0;$y < $size - 2; $y++){
                if($board->array_board[$x][$y] == $player && $board->array_board[$x-1][$y+1] == $player && $board->array_board[$x-2][$y+2]==$player){
                    //check if open end is on top-right corner of diagonal row
                    if($x - 3 >= 0 && $y+3 < $size && $board->array_board[$x-3][$y+3]==0){
                        return [$x-3,$y+3];
                    }
                    //check if open end is on bottom-left corner of the diagonal row
                    if($x + 1 < $size && $y-1 >= 0 && $board->array_board[$x+1][$y-1]==0){
                        return [$x+1,$y-1];
                    }
                }
            }
        }
        return null;
    }

    // count total connected pieces
    function count_connected($x, $y) {
        $count = 0;
        $board = $this->board;
        $size = $board->get_size();
        // possible directions
        $directions = [
            [-1, 0],  // left
            [1, 0],   // right
            [0, -1],  // up
            [0, 1],   // down
            [-1, -1], // up-left
            [-1, 1],  // up-right
            [1, -1],  // down-left
            [1, 1]    // down-right
        ];
        // count connected pieces for each direction
        foreach ($directions as [$dx, $dy]) {
            $new_x = $x + $dx;
            $new_y = $y + $dy;
            // keep counting connected pieces of same type
            if ($board->in_bounds($new_x) && $board->in_bounds($new_y) && $board->array_board[$new_x][$new_y] == 0) {
                $count++;
                $new_x += $dx;
                $new_y += $dy;
            }
        }
        return $count;
    }

    // offensive function 
    function play_smart($total_cords) {
        $board = $this->board;
        $size = $board->get_size();
        // return random coordinates if no cords are given
        if (empty($total_cords)) {
            return [random_int(0, $size - 1), random_int(0, $size - 1)];
        }
        $cord_weights = [];
        // build weighted dictionary, weight is total connected
        foreach ($total_cords as $cords) {
            $key = "{$cords[0]},{$cords[1]}";
            $cord_weights[$key] = $this->count_connected($cords[0], $cords[1]);
        }
        // cords with most connected pieces
        $max_key = array_search(max($cord_weights), $cord_weights);
        [$x, $y] = explode(',', $max_key);
        // directions for new spot
        $directions = [
            [-1, 0],  // up
            [1, 0],   // down
            [0, -1],  // left
            [0, 1],   // right
            [-1, -1], // up-left
            [-1, 1],  // up-right
            [1, -1],  // down-left
            [1, 1]    // down-right
        ];
        // find first available spot
        foreach ($directions as [$dx, $dy]) {
            $new_x = $x + $dx;
            $new_y = $y + $dy;
            // return when can fill an empty
            if ($board->in_bounds($new_x) && $board->in_bounds($new_y) && $board->array_board[$new_x][$new_y] == 0) {
                return [$new_x, $new_y];
            }
        }
    }
}
?>