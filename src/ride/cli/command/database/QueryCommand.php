<?php

namespace ride\cli\command\database;

use ride\library\database\result\DatabaseResult;

/**
 * Console command to execute a SQL query
 */
class QueryCommand extends AbstractDatabaseCommand {

    /**
     * Constructs a new query command
     * @return null
     */
    public function __construct() {
        parent::__construct('query', 'Executes a SQL query on the default database connection.');

        $this->addFlag('connection', 'Name of the connection to use');
        $this->addArgument('sql', 'The SQL script to execute', false, true);
    }

    /**
     * Executes the command
     * @param ride\core\console\InputValue $input The input
     * @param ride\core\console\output\Output $output Output interface
     * @return null
     */
    public function execute() {
        $connection = $this->input->getFlag('connection');
        $sql = $this->input->getArgument('sql');

        $connection = $this->databaseManager->getConnection($connection);

        $result = $connection->execute($sql);

        $this->writeResult($result);
    }

    /**
     * Writes the database result to the output
     * @param ride\library\database\DatabaseResult $result
     * @return null
     */
    protected function writeResult(DatabaseResult $result) {
        if (!$result->getRowCount()) {
            return null;
        }

        $columns = $result->getColumns();

        $widths = $this->calculateColumnWidths($result);
        $widths = $this->optimizeColumnWidths($widths, 80);

        $separator = array();
        foreach ($columns as $column) {
            $separator[$column] = '';
        }

        $this->output->writeLine($this->getRowOutput($separator, $widths, '-'));
        $this->output->writeLine($this->getRowOutput($columns, $widths));
        $this->output->writeLine($this->getRowOutput($separator, $widths, '-'));
        foreach ($result as $resultRow) {
            $this->output->writeLine($this->getRowOutput($resultRow, $widths));
        }
        $this->output->writeLine($this->getRowOutput($separator, $widths, '-'));
    }

    protected function getRowOutput($values, array $widths, $char = ' ') {
        $lines = 1;
        foreach ($values as $column => $value) {
            $lines = max($lines, ceil(strlen($value) / $widths[$column]));
        }

        $row = '';
        for ($line = 0; $line < $lines; $line++) {
            $row .= '|';
            foreach ($values as $column => $value) {
                $width = $widths[$column];
                $value = str_replace("\n", '', $value);

                if (mb_strlen($value) > $width) {
                    $value = substr($value, $line * $width, $width);
                } elseif ($line > 0) {
                    $value = '';
                }

                $value = $value . str_repeat($char, $width - mb_strlen($value));

                $row .= $char . $value . $char . '|';
            }
            $row .= "\n";
        }

        return substr($row, 0, -1);
    }

    protected function optimizeColumnWidths(array $widths, $maxWidth) {
        $numCols = count($widths) / 2;
        $minWidth = floor($maxWidth / $numCols);

        $remain = 0;

        $optimizeWidths = array();
        foreach ($widths as $column => $width) {
            if ($width <= $minWidth) {
                $remain += $minWidth - $width;
                continue;
            }

            $optimizeWidths[$column] = $width - $minWidth;
        }

        $total = array_sum($optimizeWidths);
        foreach ($optimizeWidths as $column => $width) {
            $percent = $width / $total;
            $widths[$column] = $minWidth + floor($remain * $percent);
        }

        return $widths;
    }

    protected function calculateColumnWidths(DatabaseResult $result) {
        $widths = array();

        $columns = $result->getColumns();
        foreach ($columns as $column) {
            $widths[$column] = mb_strlen($column);
        }

        foreach ($result as $resultRow) {
            foreach ($columns as $column) {
                $widths[$column] = max($widths[$column], mb_strlen($resultRow[$column]));
            }
        }

        $i = 0;
        foreach ($widths as $width) {
            $widths[$i] = $width;
            $i++;
        }

        return $widths;
    }

}