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
        
        $emptySpots = $this->findEmptySpots(); // makes an array of the empty spots
        
        if(empty($emptySpots)){ // checks if there are still empty spots
            echo "There are no more empty spots left on the board.";
            return null;
        }
        // if still empty spots, it returns a random value from the emptySpot array
        return $emptySpots[array_rand($emptySpots)];
        
        
    }
    
    //helper method traverse through the array and note all the empty spots and return the array
    function findEmptySpots(){
        $board = $this->board;
        $emptySpots = []; // an array to contain all empty spots on the board
        $size = $board->getSize();
        
        // checking every individual spot and seeing if its empty, if so, add the place to the emptySpots array
        for($x=0; $x<$size; $x++){
            for($y=0;$y<$size;$y++){
                if($board->isEmpty($x, $y)){
                    $emptySpots[] = [$x,$y];
                }
            }
        }
        return $emptySpots; // returns the array of empty spots
        
        
    }
    
}
?>