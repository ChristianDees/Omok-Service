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
 
}
?>