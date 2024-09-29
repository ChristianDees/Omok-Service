<?php
class Game{

    public $board;
    public $strategy;

    function __construct(){
        $this->board = new Board(2);
        // change based on user input
        $this->strategy = new RandomStrategy($this->board);
    }

    function check_win(){
        // check if five peices in a row
    }

    function check_draw() {
        // check if draw occured (all peices filled and no one has won)
    }
}
?>