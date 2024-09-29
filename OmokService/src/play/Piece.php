<?php
class Piece extends Board{

    public $x;
    public $y;
    public $player_type;
    function __construct($curr_x, $curr_y, $player_type) {
        $this->x = $curr_x;
        $this->y = $curr_y;
        $this->player_type = $player_type;
    }    

    function move_to($x, $y){
        if($this->is_valid_cord($this->x, $this->y)){
            $this->arrayBoard[$this->x][$this->y] = $this->player_type;
        }
    }

    function is_valid_cord($x, $y){
        $empty_spots = $this->findEmptySpots();
        $is_valid = true;
        foreach ($empty_spots as $list) {
            if ($list === array($this->x,$this->y)) {
                $is_valid = false;
                break;
            }
        }
        return $is_valid;
    }
}
?>