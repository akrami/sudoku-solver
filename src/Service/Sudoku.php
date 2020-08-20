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