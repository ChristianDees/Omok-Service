<?php

class Game {
    public $board;
    public $strategy;
    

    static function from_json($json) {
        $obj = json_decode($json); 
        $board_data = $obj->{'board'};
        $strategy_json = $obj->{'strategy'};
        $game = new Game();
        $game->board = Board::from_json($board_data); 
        $game->strategy = MoveStrategy::from_json($strategy_json, $game->board);
        return $game;
    }

    function to_json() {
        return json_encode([
            'board' => $this->board->to_json(),
            'strategy' => $this->strategy->to_json()
        ]);
    }

    function is_win() {
        // Check if five pieces in a row
        return false;
    }

    function is_draw() {
        // Check if draw occurred (all pieces filled and no one has won)
        return false;
    }
}
?>