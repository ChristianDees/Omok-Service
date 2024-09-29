<?php
require_once 'Board.php';
require_once 'MoveStrategy.php';

class RandomStrategy extends MoveStrategy{
    
    
    function pickPlace(){
        $board = $this->board;
        $size = $board->getSize();
        
        // random selection - side note this may be redudant so modify later if neccessary
        $x = rand(0,$size-1);
        $y = rand(0,$size-1);
        if ($board->isEmpty($x,$y)){
            return [$x,$y];
        }
        
        // IN THE CASE THAT THE RANDOM SPOT WAS NOT EMPTY
        
        $emptySpots = $board->findEmptySpots(); // makes an array of the empty spots
        
        if(empty($emptySpots)){ // checks if there are still empty spots
            echo "There are no more empty spots left on the board.";
            return null;
        }
        // if still empty spots, it returns a random value from the emptySpot array
        return $emptySpots[array_rand($emptySpots)];
    }
}
?>