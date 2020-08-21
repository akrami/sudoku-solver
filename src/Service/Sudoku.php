<?php


namespace App\Service;


class Sudoku
{
    protected $grid;

    /**
     * Sudoku constructor.
     * @param array $grid
     */
    protected function __construct(array $grid)
    {
        $this->grid = $grid;
    }

    /**
     * import a grid as string (123...789)
     * @param string $grid
     * @return Sudoku|null
     */
    public static function importString(string $grid)
    {
        if (strlen($grid) === 81 && preg_match('/^[\d]{81}$/', $grid)) {
            return new Sudoku(
                array_map(function ($row) {
                    return str_split($row);
                }, str_split($grid, 9)));
        }
        return null;
    }

    /**
     * grid getter
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * get an array of a row
     * @param int $x
     * @return array
     */
    private function getRow(int $x): array
    {
        return $this->grid[$x];
    }

    /**
     * get an array of a column
     * @param int $y
     * @return array
     */
    private function getColumn(int $y): array
    {
        return array_map(function ($row) use ($y) {
            return $row[$y];
        }, $this->grid);
    }

    /**
     * get a linear array of 3x3 box
     * @param int $x
     * @param int $y
     * @return array
     */
    private function getBox(int $x, int $y): array
    {
        $x1 = floor($x / 3) * 3;
        $x2 = $x1 + 2;
        $y1 = floor($y / 3) * 3;
        $y2 = $y1 + 2;
        $result = array();
        for ($i = $x1; $i <= $x2; $i++) {
            for ($j = $y1; $j <= $y2; $j++) {
                array_push($result, $this->grid[$i][$j]);
            }
        }
        return $result;
    }

    /**
     * check for possible elements that fit this element
     * @param int $x
     * @param int $y
     * @return array
     */
    private function getPossibles(int $x, int $y): array
    {
        $notPossibles = array_unique(array_merge($this->getRow($x), $this->getColumn($y), $this->getBox($x, $y)));
        $allPossibles = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        return array_filter($allPossibles, function ($each) use ($notPossibles) {
            return !in_array($each, $notPossibles);
        });
    }

    /**
     * check for the next empty element
     * @return bool|int[]
     */
    private function nextEmpty()
    {
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if ($this->grid[$i][$j] === "0") return ["x" => $i, "y" => $j];
            }
        }
        return false;
    }

    public function solve()
    {
        $point = $this->nextEmpty();
        if ($point === false) return true;

        $possibles = $this->getPossibles($point["x"], $point["y"]);
        if (count($possibles) === 0) return false;

        foreach ($possibles as $possible) {
            $this->grid[$point["x"]][$point["y"]] = $possible;
            if ($this->solve()) return true;
        }
        $this->grid[$point["x"]][$point["y"]] = "0";
        return false;
    }

    /** return the grid as boxed string
     * @return string
     */
    public function printGrid(): string
    {
        $result = "";
        foreach ($this->grid as $x => $row) {
            foreach ($row as $y => $value) {
                if ($y % 3 === 0 and $y !== 0) $result .= "| ";
                $result .= $value . " ";
            }
            $result .= "\n";
            if (($x + 1) % 3 === 0 and $x !== 8) $result .= "---------------------\n";
        }
        return $result;
    }

    /**
     * return the grid as linear string
     * @return string
     */
    public function __toString(): string
    {
        return implode(
            array_map(function ($row) {
                return implode($row);
            }, $this->grid)
        );
    }
}