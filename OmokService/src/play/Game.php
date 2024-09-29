<?php

class Game {
    public $board;
    public $strategy;

    static function fromJson($json) {
        $obj = json_decode($json); 

        $boardData = $obj->{'board'};
        $strategyJson = $obj->{'strategy'};

        $game = new Game();
        $game->board = Board::fromJson($boardData); 

        
        $game->strategy = MoveStrategy::fromJson($strategyJson, $game->board);
        
        return $game;
    }

    function toJson() {
        return json_encode([
            'board' => $this->board->toJson(),
            'strategy' => $this->strategy->toJson()
        ]);
    }

    function check_win() {
        // Check if five pieces in a row
        return false;
    }

    function check_draw() {
        // Check if draw occurred (all pieces filled and no one has won)
        return false;
    }
}


?>