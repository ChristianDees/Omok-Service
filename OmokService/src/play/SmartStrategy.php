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
                    $total_cords[] = array($x, $y);
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

    // count total spots around for free
    function count_around($x, $y){
        $count = 0;
        $board = $this->board;
        $size = $this->board->get_size();
        if($x>0 && $board->array_board[$x-1][$y] == 0){
            $count+=1;
        }
        // check if there is an open right side
        if($x+1 < $size && $board->array_board[$x+1][$y]==0){
            $count+=1;
        }
        //CHECK OPEN SPACE VERTICALLY
        if($y>0 && $board->array_board[$x][$y-1] == 0){
            $count+=1;
        }
        if($y+1 < $size && $board->array_board[$x][$y+1] == 0){
            $count+=1;
        }
        return $count;
    }
    
    // offensive function
    function play_smart($total_cords){
        $board = $this->board;
        $size = $board->get_size();
        // get a random first cord
        if ($total_cords === array()){
            return array(random_int(0, $size -1), random_int(0, $size -1));
        }
        $cord_weights = array();
        // weighted dictionary
        foreach ($total_cords as $cords) {
            $key = $cords[0] . ',' . $cords[1]; 
            $cord_weights[$key] = $this->count_around($cords[0], $cords[1]); 
        }
        // split cords into x and y
        $maxi = max($cord_weights);
        $largest_cords = explode(',',array_search(max($cord_weights), $cord_weights));
        $x = $largest_cords[0];
        $y = $largest_cords[1];
        // place priority cords in new spot that aren't empty
        do {
            if($x>0 && $board->array_board[$x-1][$y] == 0){
                return [$x-1,$y];
            }
            // check if there is an open right side
            if($x+1 < $size && $board->array_board[$x+1][$y]==0){
                return [$x+1,$y];
            }
            //CHECK OPEN SPACE VERTICALLY
            if($y>0 && $board->array_board[$x][$y-1] == 0){
                return [$x,$y-1];
            }
            if($y+1 < $size && $board->array_board[$x][$y+1] == 0){
                return [$x,$y+1];
            }
        } while($board->is_empty($x,$y));
        
    }
}
?>