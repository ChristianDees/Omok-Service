<?php
require_once 'Board.php';
require_once 'RandomStrategy.php';

$board = new Board(2);
$strategy = new RandomStrategy($board);

// Fill some spots to simulate the game state
$board->arrayBoard[0][0] = 1; // Marking (0,0) as filled
$board->arrayBoard[0][1] = 1; // Marking (0,1) as filled
$board->arrayBoard[1][0] = 1; // Marking (1,0) as filled
$board->arrayBoard[1][1] = 1; // Marking (1,1) as filled

// As such, the four test should return no empty places available, as all spots are covered.

// Test the pickPlace method
for ($i = 0; $i < 4; $i++) {
    $place = $strategy->pickPlace();
    if ($place !== null) {
        echo "Selected place: (" . $place[0] . ", " . $place[1] . ")\n";
    } else {
        echo "No empty places available.\n";
    }
}
?>
