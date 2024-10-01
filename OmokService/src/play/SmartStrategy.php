<?php

require_once "Board.php";
require_once "MoveStrategy.php";
    
class SmartStrategy extends MoveStrategy{
    
    function pickPlace(){
        //intialize the board
        $board = $this->board;
        $place = $this->checkThreeWithOpenEndOpponent(2);
        return $place;

        
        //see if win
        //see if block
        //return random otherwise
        
    }
    
    function checkThreeWithOpenEndOpponent($player){
        $board = $this->board;
        $size = $board->getSize();
        //check vertical row
        for($x=0;$x<$size;$x++){
            for($y=0;$y<$size - 2;$y++){
                if($board->arrayBoard[$x][$y]== $player && $board->arrayBoard[$x][$y + 1]== $player && $board->arrayBoard[$x][$y + 2]== $player){
                    // check that the next two spaces are filled
                    // check if the open end is on the top of the row
                    if($y>0 && $board->arrayBoard[$x][$y-1] == 0){
                        return [$x,$y-1];
                    }
                    // check if the open end is on the bottom of the row
                    if($y+3<$size && $board->arrayBoard[$x][$y+3] == 0)
                        return [$x,$y+3];
                }
            }
        }
        //check horizontal row
        for($x=0;$x<$size-2;$x++){
            for($y=0;$y<$size;$y++){
                if($board->arrayBoard[$x][$y]== $player && $board->arrayBoard[$x+1][$y]== $player && $board->arrayBoard[$x+2][$y]== $player){
                    // checks if open end is on left side
                    if($x>0 && $board->arrayBoard[$x-1][$y] == 0){
                        return [$x-1,$y];
                    }
                    // checks if open end is on right side
                    if($x+3<$size && $board->arrayBoard[$x+3][$y] == 0)
                        return [$x+3,$y];
                }
            }
        }
        
        //check diagonal row (bottom-right)
        for($x=0;$x<$size-2;$x++){
            for($y=0;$y<$size-2;$y++){
                if($board->arrayBoard[$x][$y]==$player && $board->arrayBoard[$x+1][$y+1]== $player && $board->arrayBoard[$x+2][$y+2] == $player){
                    //check if open end is on top-left corner of the diagonal row
                    if($x>0 && $board->arrayBoard[$x-1][$y-1]==0){
                        return [$x-1,$y-1];
                    }
                    //check if open end is on bottom-right corner of the diagonal row
                    if($x+3>0 && $board->arrayBoard[$x+3][$y+3]==0){
                        return [$x+3,$y+3];
                    }
                }
            }
        }
        
        //check diagonal row (top-right)
    
        for($x=2; $x < $size; $x++){
            for($y=0;$y < $size - 2; $y++){
                if($board->arrayBoard[$x][$y] == $player && $board->arrayBoard[$x-1][$y+1] == $player && $board->arrayBoard[$x-2][$y+2]==$player){
                    //check if open end is on top-right corner of diagonal row
                    if($x - 3 >= 0 && $y+3 < $size && $board->arrayBoard[$x-3][$y+3]==0){
                        return [$x-3,$y+3];
                    }
                    //check if open end is on bottom-left corner of the diagonal row
                    if($x + 1 < $size && $y > 0 && $board->arrayBoard[$x+1][$y-1]){
                        return [$x+1,$y-1];
                    }
                }
            }
        }
        
        
    }
    
}
?>
