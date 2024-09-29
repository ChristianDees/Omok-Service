<?php
require_once 'Board.php';
require_once 'RandomStrategy.php';

$board = new Board(2);
$strategy = new RandomStrategy($board);

// Fill some spots to simulate the game state


// As such, the four test should return no empty places available, as all spots are covered.

// Test the pickPlace method
for ($i = 0; $i < 4; $i++) {
    $place = $strategy->pickPlace();
    if ($place !== null) {
        echo "Selected place: (" . $place[0] . ", " . $place[1] . ")\n";
    }
}
?>
