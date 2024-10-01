<?php

class Game {
    public $board;
    public $strategy;
    

    // assign game info from json str
    static function from_json($json) {
        $obj = json_decode($json); 
        $board_data = $obj->{'board'};
        $strategy_json = $obj->{'strategy'};
        $game = new Game();
        $game->board = Board::from_json($board_data); 
        $game->strategy = MoveStrategy::from_json($strategy_json, $game->board);
        return $game;
    }

    // json encode game info
    function to_json() {
        return json_encode([
            'board' => $this->board->to_json(),
            'strategy' => $this->strategy->to_json()
        ]);
    }

    // return (player_type, winning row)
    function win_info() {
        // return array (player_type, winning row)
        // Check if five pieces in a row 
        //return [0, [1,2,3,4,5,6]];
        
        return array();
    }

    // return (usr_winning_row, sys_winning_row)
    function draw_info() {
        // Check if draw occurred (all pieces filled and no one has won)
        // return array (usr_winning_row, sys_winning_row)
        return array();
    }
}
?>