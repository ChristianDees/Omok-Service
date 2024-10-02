<?php

require_once "Board.php";

class Game {
    
    public $board;
    public $strategy;
    
    public function __construct(Board $board = null){
        $this->board = $board;
    }
    

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

    function is_win($player) {
        // Check if five pieces in a row
        $board = $this->board;
        $size = $board->get_size();
        
        // check vertical winning row
        for($x=0;$x<$size;$x++){
            for($y=0;$y<=$size - 5;$y++){
                if($board->array_board[$x][$y]== $player && $board->array_board[$x][$y + 1]== $player && $board->array_board[$x][$y + 2] == $player && $board->array_board[$x][$y + 3] == $player && $board->array_board[$x][$y + 4] == $player){
                    return ['win' => true, 'coordinates' =>[$x,$y,$x,$y+1,$x,$y+2,$x,$y+3,$x,$y+4]];;
                }
            }
        }
        // check horizontal winning row
        for($x=0;$x<=$size-5;$x++){
            for($y=0;$y<$size;$y++){
                if($board->array_board[$x][$y]== $player && $board->array_board[$x+1][$y]== $player && $board->array_board[$x+2][$y]== $player && $board->array_board[$x+3][$y]== $player && $board->array_board[$x+4][$y]== $player){
                    return ['win' => true, 'coordinates' =>[$x,$y,$x+1,$y,$x+2,$y,$x+3,$y,$x+4,$y]];
                }
            }
        }
        // check diagonal from top to bottom
        
        for($x=0;$x<=$size-5;$x++){
            for($y=0;$y<=$size-5;$y++){
                if($board->array_board[$x][$y]==$player && $board->array_board[$x+1][$y+1]== $player && $board->array_board[$x+2][$y+2] == $player && $board->array_board[$x+3][$y+3] == $player && $board->array_board[$x+4][$y+4] == $player){
                    return ['win' => true, 'coordinates' =>[$x,$y,$x+1,$y+1,$x+2,$y+2,$x+3,$y+3,$x+4,$y+4]];
                }
            }
        }
        // check diagonal from bottom to top
        
        for($x=4; $x < $size; $x++){
            for($y=0;$y <= $size - 5; $y++){
                if($board->array_board[$x][$y] == $player && $board->array_board[$x-1][$y+1] == $player && $board->array_board[$x-2][$y+2]==$player && $player && $board->array_board[$x-3][$y+3]==$player && $player && $board->array_board[$x-4][$y+4]==$player){
                    return ['win' => true, 'coordinates' =>[$x,$y,$x-1,$y+1,$x-2,$y+3,$x-3,$y+3,$x-4,$y+4]];
                }
            }
        }
        
        return ['win' => false, 'coordinates' =>[]];
    }

    function is_draw() {
        $board = $this->board;
        $size = $board->get_size();
        
        for($x=0;$x<$size;$x++){
            for($y=0;$y<$size;$y++){
                if($board->is_empty($x, $y)){
                    return false; // spaces are still empty, game hasn't ended
                }
            }
        }
        
        if(!$this->is_win(1)['win'] && !$this->is_win(2)['win'] ){
            return true; // spaces are filled no one won, it's a draw
        }
        return false; // not a draw, none of the players won
    }
}
?>
