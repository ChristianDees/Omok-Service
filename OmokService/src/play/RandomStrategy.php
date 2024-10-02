<?php
require_once 'Board.php';
require_once 'MoveStrategy.php';

class RandomStrategy extends MoveStrategy{
    
    function pick_place(){
        $board = $this->board;
        $size = $board->get_size();
        
        // random selection - side note this may be redudant so modify later if neccessary
        $x = rand(0,$size-1);
        $y = rand(0,$size-1);
        if ($board->is_empty($x,$y)){
            return [$x,$y];
        }
        
        // IN THE CASE THAT THE RANDOM SPOT WAS NOT EMPTY
        
        $empty_spots = $board->find_empty_spots(); // makes an array of the empty spots
        
        if(empty($empty_spots)){ // checks if there are still empty spots
            return null;
        }
        // if still empty spots, it returns a random value from the emptySpot array
        return $empty_spots[array_rand($empty_spots)];
    }
}
?>