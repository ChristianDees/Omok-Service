<?php
class Player {
    private $board;
    public $player_type;

    function __construct($player_type, Board $board) {
        $this->player_type = $player_type;
        $this->board = $board; 
    }    

    function move_to($x, $y) {
        if ($this->board->isEmpty($x, $y)) {
            $this->board->arrayBoard[$x][$y] = $this->player_type; 
        }
    }
}

?>