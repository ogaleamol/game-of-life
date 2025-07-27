<?php

class GameOfLife
{
    private array $grid;
    private int $rows;
    private int $cols;

    public function __construct(array $initialGrid)
    {
        $this->grid = $initialGrid;
        $this->rows = count($initialGrid);
        $this->cols = count($initialGrid[0]);
    }

    public function tick(): void
    {
        $newGrid = $this->grid;

        for ($x = 0; $x < $this->rows; $x++) {
            for ($y = 0; $y < $this->cols; $y++) {  
                $aliveNeighbors = $this->countAliveNeighbors($x, $y); 
                $cell = $this->grid[$x][$y];

                if ($cell === 1) {
                    // Live cell dies unless it has 2 or 3 live neighbors
                    $newGrid[$x][$y] = ($aliveNeighbors === 2 || $aliveNeighbors === 3) ? 1 : 0;
                } else {
                    // Dead cell becomes alive if it has exactly 3 live neighbors
                    $newGrid[$x][$y] = ($aliveNeighbors === 3) ? 1 : 0;
                }
            }
        }

        $this->grid = $newGrid;
    }

    private function countAliveNeighbors(int $x, int $y): int
    {
        $count = 0;

        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                if ($i === 0 && $j === 0) continue;
                $nx = $x + $i;
                $ny = $y + $j;

                if ($nx >= 0 && $nx < $this->rows && $ny >= 0 && $ny < $this->cols) {
                    $count += $this->grid[$nx][$ny];
                }
            }
        }

        return $count;
    }

    public function render(): void
    {
        foreach ($this->grid as $row) {
            foreach ($row as $cell) {
               echo $cell ? '1' : '0';
            }
            echo PHP_EOL;
        }
    }

    public function run(int $generations, int $delay = 500000, bool $showAllGrids = false): void
    {
        for ($i = 0; $i < $generations; $i++) {
            if ($showAllGrids === false) { 
                echo "\033[H\033[J"; // Clear terminal
            }
            echo "Generation " . ($i + 1) . PHP_EOL;
            $this->render();
            $this->tick();
            usleep($delay); // Pause between generations
        }
    }
}


$initialGrid = [
    [0, 0, 0],
    [1, 1, 1],
    [0, 0, 0],
];


$generation = (int) $argv[1] ?? 10;
$showAllGrids = (bool)$argv[2] ?? false;

$game = new GameOfLife($initialGrid);
$game->run( $generation, 500000, $showAllGrids); // 10 generations