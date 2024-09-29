<?php
abstract class MoveStrategy {
    protected $board;

    function __construct(Board $board = null) {
        $this->board = $board;
    }

    abstract function pickPlace();

    function toJson() {
        return json_encode(['name' => get_class($this)]);
    }

    static function fromJson($json, Board $board) {
        $data = json_decode($json, true); 
        $strategyClassName = $data['name'];
        // return new strat obj of type strat
        if (class_exists($strategyClassName)) {
            return new $strategyClassName($board); 
        }
    }
}

?>