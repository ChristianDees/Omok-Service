<?php

// Authors: Christian Dees & Aitiana Mondragon

require_once 'Board.php';
require_once 'MoveStrategy.php';

class RandomStrategy extends MoveStrategy{
    
    function pick_place(){
        $board = $this->board;
        
        // random selection process
        $empty_spots = $board->find_empty_spots(); // an array of the empty spots
        
        if(empty($empty_spots)){ // checks if there are no empty spots
            return null; // if there aren't any empty spots, returns null
        }
        // if there are still empty spots, it returns a random value from the emptySpot array
        return $empty_spots[array_rand($empty_spots)];
    }
}
?>
