<?php
class Piece {
    public $board;
    public $piece_type;

    function __construct($piece_type, Board $board) {
        $this->piece_type = $piece_type;
        $this->board = $board; 
    }    

    // place a piece at (x, y)
    function place($x, $y) {
        $reason = null;
        $cord = null;
        // check if x is valid
        if ($this->board->isOutOfBounds($x)) {
            $reason = 'x';
            $cord = $x;
        // check if y is valid
        } else if ($this->board->isOutOfBounds($y)) {
            $reason = 'y';
            $cord = $y;
        }
        if ($reason && $cord){
            return array("response" => false, "reason" => "Invalid $reason coordinate, " . $cord);
        }
        // place if cords are available
        else if ($this->board->isEmpty($x, $y)) {
            $this->board->arrayBoard[$x][$y] = $this->piece_type;
        } else {
            return array("response" => false, "reason" => "Place not empty, ($x, $y)");
        }
        // return 0 if success 
        return 0;
    }
}
?>