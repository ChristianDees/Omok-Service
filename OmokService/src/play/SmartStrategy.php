<?php

require_once "Board.php";
require_once "MoveStrategy.php";
    
class SmartStrategy extends MoveStrategy{
    
    function pickPlace(){
        
        // checks if can win
        
        $board = $this->board;
        
        $win_place = $this->check_open_end(1);
        if($win_place !== null){
            return $win_place;
        }
        
        // checks if block is neccessary
        $block_place = $this->check_open_end(2);
        if($block_place !== null){
            return $block_place;
        }
        
        // if neither, random spot is chosen
        
        $emptySpots = $board->find_empty_spots();
        return $emptySpots[array_rand($emptySpots)];

        

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
                    if($x + 1 < $size && $y-1 >= 0 && $board->array_board[$x+1][$y-1]){
                        return [$x+1,$y-1];
                    }
                }
            }
        }
        return null;
        
    }
    
    //function play_smart(){
        
    //}
    
}
?>
