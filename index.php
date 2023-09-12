<?php

require_once __DIR__ . '/vendor/autoload.php';

use Femi\Battle\BattleshipGame;
use Femi\Battle\GameGrid;
use Femi\Battle\Ship;

// Create a game grid
$grid = new GameGrid();

// Create ships (You can customize ship types and sizes)
$ships = [
    new Ship('Battleship', 5),
    new Ship('Destroyer 1', 4),
    new Ship('Destroyer 2', 4),
];

$game = new BattleshipGame($grid, $ships);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['target'])) {
        $game->start(); // Remove the parameter here
    }

    if (isset($_POST['restart'])) {
        $game->restartGame();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battleships</title>
</head>
<body>
<h1>Welcome to Battleships!</h1>
<p>Try to sink all the ships on the grid.</p>

<form method="POST" action="index.php">
    <label for="target">Enter target (e.g., A5): </label>
    <input type="text" id="target" name="target">
    <button type="submit">Fire!</button>
</form>

<form method="POST" action="index.php">
    <button type="submit" name="restart">Restart Game</button>
</form>

<h2>Player Grid</h2>
<?php
// Display the player grid
$game->displayPlayerGrid();
?>

<h2>Remaining Ships</h2>
<?php
// Count remaining ships
$remainingShips = $game->countRemainingShips();
echo "Remaining ships: $remainingShips";
?>

<h2>Cell Status</h2>
<?php
// Example: Get and display the status of cell A1
$cellStatus = $game->getCellStatus(0, 0); // Replace with the desired coordinates
echo "Cell A1 Status: $cellStatus";
?>

</body>
</html>
