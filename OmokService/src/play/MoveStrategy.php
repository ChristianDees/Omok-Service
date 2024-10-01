<?php
abstract class MoveStrategy {
    protected $board;

    function __construct(Board $board = null) {
        $this->board = $board;
    }

    abstract function pick_place();

    function to_json() {
        return json_encode(['name' => get_class($this)]);
    }

    static function from_json($json, Board $board) {
        $data = json_decode($json, true); 
        $strat_class = $data['name'];
        // return new strat obj of type strat
        if (class_exists($strat_class)) {
            return new $strat_class($board); 
        }
    }
}

?>