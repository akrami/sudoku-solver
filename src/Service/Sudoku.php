<?php


namespace App\Service;


class Sudoku
{
    protected $grid;
    protected $fixed;

    /**
     * Sudoku constructor.
     * @param array $grid
     */
    protected function __construct(array $grid)
    {
        $this->grid = $grid;

        // generate `fixed` as a 9x9 false matrix
        $this->fixed = array_fill(0,9,array_fill(0,9, false));

        // now fill `fixed` based on `grid` values
        foreach ($this->grid as $rowIndex => $row) {
            foreach ($row as $colIndex=>$value) {
                if ($value!=="0") $this->fixed[$rowIndex][$colIndex] = true;
            }
        }
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
     * fixed getter
     * @return array
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * __toString
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