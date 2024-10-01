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
        return ($this->arrayBoard[$x][$y] == 0) && ($x >= 0 && $x < $this->size) && ($y >= 0 && $y < $this->size);
    }

    function isOutOfBounds($cord){
        return ($cord >= $this->size) || ($cord < 0);
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
    

    // Generative AI: temporary display table 
    function display() {
        echo "<table style='border-collapse: collapse;'>";
        echo "<tr><td style='border: 1px solid black;'></td>"; 
        for ($y = 0; $y < $this->size; $y++) {
            echo "<td style='border: 1px solid black; padding: 10px; text-align: center;'>" . $y . "</td>";
        }
        echo "</tr>";
        for ($x = 0; $x < $this->size; $x++) {
            echo "<tr>"; 
            echo "<td style='border: 1px solid black; padding: 10px; text-align: center;'>" . $x . "</td>"; 
            for ($y = 0; $y < $this->size; $y++) {
                
                echo "<td style='border: 1px solid black; padding: 10px; text-align: center;'>" . $this->arrayBoard[$x][$y] . "</td>";
            }
            echo "</tr>"; 
        }
        echo "</table>"; 
    }
    
    

    // board metadata to json
    function toJson() {
        return json_encode([
            'size' => $this->size,
            'arrayBoard' => $this->arrayBoard,
        ]);
    }


    // create instance of board from json
    static function fromJson($json) {
        $data = json_decode($json, true); 
        $board = new Board($data['size']);
        $board->arrayBoard = $data['arrayBoard'];
        return $board;
    }
}
?>