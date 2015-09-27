<?php

namespace ride\cli\command\database;

use ride\cli\command\AbstractCommand;

use ride\library\database\result\DatabaseResult;
use ride\library\database\DatabaseManager;
use ride\library\system\System;

use \Exception;

/**
 * Command to execute an SQL query on a database connection
 */
class QueryCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Executes a SQL query on the default database connection.');

        $this->addFlag('connection', 'Name of the connection to use');
        $this->addArgument('sql', 'The SQL script to execute', false, true);
    }

    /**
     * Invokes the command
     * @param \ride\library\database\DatabaseManager $databaseManager
     * @param \ride\library\system\System $system
     * @param string $sql
     * @return null
     */
    public function invoke(DatabaseManager $databaseManager, System $system, $sql) {
        $connection = $this->input->getFlag('connection');
        $connection = $databaseManager->getConnection($connection);

        $result = $connection->execute($sql);

        $this->writeResult($system, $result);
    }

    /**
     * Writes the database result to the output
     * @param \ride\library\system\System $system
     * @param \ride\library\database\result\DatabaseResult $result
     * @return null
     */
    protected function writeResult(System $system, DatabaseResult $result) {
        if (!$result->getRowCount()) {
            return null;
        }

        try {
            $output = $system->execute('tput cols');
            $maxColumnWidth = array_shift($output);
        } catch (Exception $exception) {
            $maxColumnWidth = 120;
        }

        $columns = $result->getColumns();

        $widths = $this->calculateColumnWidths($result);
        $widths = $this->optimizeColumnWidths($widths, $maxColumnWidth);

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

    /**
     * Gets the output for one row
     * @param array $values Values for the columns
     * @param array $widths Widths of the columns in number of characters
     * @param string $char Char to fill up the column value
     * @return string Row output
     */
    protected function getRowOutput(array $values, array $widths, $char = ' ') {
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

    /**
     * Optimizes the provided widths for a maximum width
     * @param array $widths Array with the column widths
     * @param integer $maxWidth Maximum width
     * @return array Array with the optimized column widths
     */
    protected function optimizeColumnWidths(array $widths, $maxWidth) {
        $maxWidth -= count($widths) + 1; // remove separators
        $minWidth = floor($maxWidth / (count($widths) / 2)) - 1;
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

    /**
     * Calculates the column widths based on the result values
     * @param \ride\library\database\result\DatabaseResult $result
     * @return array Array with the column index as key and the width as value
     */
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