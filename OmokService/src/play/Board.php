<?php
class Board{
    
    public $size;
    public $array_board;
    
    function __construct($size){
        $this->size = $size;
        $this->array_board = array_fill(0,$size,array_fill(0,$size,0));
    }
    
    function get_size(){
        return $this->size;
    }
    
    function is_empty($x,$y){
        return ($this->array_board[$x][$y] == 0) && ($x >= 0 && $x < $this->size) && ($y >= 0 && $y < $this->size);
    }

    // return if cord is out of bounds
    function is_oob($cord){
        return ($cord >= $this->size) || ($cord < 0);
    }

    //helper method traverse through the array and note all the empty spots and return the array
    function find_empty_spots(){
        $empty_spots = []; // an array to contain all empty spots on the board
        $size = $this->get_size();
        
        // checking every individual spot and seeing if its empty, if so, add the place to the emptySpots array
        for($x=0; $x<$size; $x++){
            for($y=0;$y<$size;$y++){
                if($this->is_empty($x, $y)){
                    $empty_spots[] = [$x,$y];
                }
            }
        }
        return $empty_spots; // returns the array of empty spots
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
                
                echo "<td style='border: 1px solid black; padding: 10px; text-align: center;'>" . $this->array_board[$x][$y] . "</td>";
            }
            echo "</tr>"; 
        }
        echo "</table>"; 
    }
    

    // board metadata to json
    function to_json() {
        return json_encode([
            'size' => $this->size,
            'array_board' => $this->array_board,
        ]);
    }

    // create instance of board from json
    static function from_json($json) {
        $data = json_decode($json, true); 
        $board = new Board($data['size']);
        $board->array_board = $data['array_board'];
        return $board;
    }
}
?>