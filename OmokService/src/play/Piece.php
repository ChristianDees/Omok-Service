<?php

// Authors: Christian Dees & Aitiana Mondragon

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
        if (!($this->board->in_bounds($x))) {
            $reason = 'x';
            $cord = $x;
        // check if y is valid
        } else if (!($this->board->in_bounds($y))) {
            $reason = 'y';
            $cord = $y;
        }
        if ($reason && $cord){
            return array("response" => false, "reason" => "Invalid $reason coordinate, " . $cord);
        }
        // place if cords are available
        else if ($this->board->is_empty($x, $y)) {
            $this->board->array_board[$x][$y] = $this->piece_type;
        } else {
            return array("response" => false, "reason" => "Place not empty, ($x, $y)");
        }
        // return 0 if success 
        return 0;
    }
}
?>
