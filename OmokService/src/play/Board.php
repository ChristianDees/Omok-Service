<?php
class Board{
    
    public $size;
    public $arrayBoard;
    
    function __construct($size){
        $this->size = $size;
        $this->arrayBoard = array_fill(0,$size,array_fill(0,$size,0));
    }
    
    function getSize(){
        return $this->size;
    }
    
    function isEmpty($x,$y){
        return $this->arrayBoard[$x][$y] == 0;
    }

    //helper method traverse through the array and note all the empty spots and return the array
    function findEmptySpots(){
        $emptySpots = []; // an array to contain all empty spots on the board
        $size = $this->getSize();
        
        // checking every individual spot and seeing if its empty, if so, add the place to the emptySpots array
        for($x=0; $x<$size; $x++){
            for($y=0;$y<$size;$y++){
                if($this->isEmpty($x, $y)){
                    $emptySpots[] = [$x,$y];
                }
            }
        }
        return $emptySpots; // returns the array of empty spots
    }
 
}
?>